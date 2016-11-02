<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method
/-------------------------------------------------------------------------------------------------------/

	@version			1.0.0 - 01st July, 2015
	@package		Dropbox Links builder
	@subpackage		dropboxlinks.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

/**
 * Dropbox class
 */
class Dropbox
{
	// the file name
	public $fileName;

	// the file array
	public $files = array();

	// the extension to get
	protected $getfiles = array();

	// the extensions to leave
	protected $notfiles = array();

	// the folder array
	protected $folders = array();

	// folder identifiers
	protected $getfolders = array('?dl=0');

	// the list of extension to help identify what is folders (not exhaustive, only some of the commen files)
	protected $notfolders = array(
		'.3gp','.3gpp','.7z','.aac','.act','.aiff','.aiff','.amr','.ape','.ar','.asf','.au','.avi','.awb','.bmp','.bup','.bzip2','.crx','.css','.dct','.deb','.djvu',
		'.doc','.docx','.drc','.dss','.ear','.egg','.exe','.flac','.flv','.gif','.gifv','.gzip','.htaccess','.html','.ico','.ini','.iso','.jar','.jpeg','.jpg','.js',
		'.json','.lzip','.m3u','.m4a','.m4p','.m4v','.mkv','.mmf','.mng','.mov','.mp3','.mp4','.mpc','.mpeg','.mpeg4','.mpegps','.mpg','.mpkg','.msi','.odt','.ogg',
		'.opus','.pdf','.pea','.php','.pkg','.png','.ppt','.pptx','.ps','.psd','.pst','.pxv','.rar','.raw','.rm','.rmvb','.rpm','.rtf','.shar','.sql','.svg','.sxw',
		'.tar','.tgz','.tgz','.tiff','.txt','.veg','.vep','.vob','.wav','.webm','.wma','.wmv','.xar','.xls','.xml','.yuv','.zip' );

	// the directory where the downloaded files should be stored
	protected $dir;

	/**
	 * Constructor
	 */
	public function __construct($mainurl, $config = array('save' => false, 'filename' => 'dropbox', 'download' => false, 'dir' => __DIR__))
	{
		if ($this->checkArray($config))
		{
			// we need more then the normal time to run this script 5 minutes at least.
			ini_set('max_execution_time', 500); // TODO this is not the best option I am sure, but for now seems like the only option.
			// set main url
			$this->mainurl = $mainurl;
			// if custom get list is set use it
			if (isset($config['get']) && $this->checkArray($config['get']))
			{
				$this->getfiles = $config['get'];
			}
			elseif (isset($config['get']) && $this->checkString($config['get']))
			{
				$this->getfiles = array($config['get']);
			}
			// if custom excludelist is set use it
			if (isset($config['not']) && $this->checkArray($config['not']))
			{
				$this->notfiles = $config['not'];
			}
			elseif (isset($config['not']) && $this->checkString($config['not']))
			{
				$this->notfiles = array($config['not']);
			}
			// set main folder name
			$this->fileName = (isset($config['filename'])) ? md5($this->mainurl.$config['filename']) : md5($this->mainurl.'nofilenamewasset');
			// set save switch
			$save = (isset($config['save'])) ? $config['save'] : false;
			// set download switch
			$download = (isset($config['download'])) ? $config['download'] : false;
			// make sure the note folder identifiers are complete
			$this->notfolders = array_unique(array_merge($this->getfiles,$this->notfiles,$this->notfolders));
			// set local directory
			$this->dir = (isset($config['dir'])) ? $config['dir'] : __DIR__;
			// check if the parching of the drobox files is up to date
			if (($json = @file_get_contents($this->fileName.".json")) !== FALSE)
			{
				$this->files = json_decode($json, true);
			}
			else
			{
				// set all folders
				$this->parseFolders($this->mainurl, 'VDM_pLeK_h0uEr');
			}
			// check if we should save the file
			if ($save)
			{
				$this->save();
			}
			// check if we should download the file
			if ($download)
			{
				$this->download();
			}
			return true;
		}
		return false;
	}

	public function download($dir = null)
	{
		if ($this->checkArray($this->files))
		{
			// insure the directory is set
			$dir = ($dir) ? $dir : $this->dir;
			foreach($this->files as $path => $url)
			{
				// now setup correct path
				$path = str_replace('VDM_pLeK_h0uEr', $dir, $path);
				// boom here we go move....
				$this->getFile($url, $path);
			}
		}
	}

	public function getFile($url, $path)
	{
		$newfname = $path;
		if (($file = @fopen($url, "rb")) !== FALSE)
		{
			$filename = substr($path, strrpos($path, '/'));
			$makePath = str_replace($filename,'',$path);
			if (!file_exists($makePath))
			{
				mkdir($makePath, 0755, true);
			}

			$newf = fopen($newfname, "wb");
		}
		else
		{
			echo 'error! '.$url.' not found';
		}

		if ($newf)
		{
			while(!feof($file))
			{
			  fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}

		if ($file)
		{
			fclose($file);
		}

		if ($newf)
		{
			fclose($newf);
		}
	}

	public function save($filename = null)
	{
		if ($this->checkString($filename))
		{
			// set main folder name
			$this->fileName = md5($this->mainurl.$filename);
		}
		if ($this->checkArray($this->files))
		{
			$data = json_encode($this->files);
			// now save this folders data to a file.
			$this->saveJson($data);

			return true;
		}
		return false;
	}

	protected function saveJson($data)
	{
		if ($this->checkString($data))
		{
			/*
				for now it will save
				the files in the same
				directory as the php
				file where the class
				is called we will have
				to change this to suite
				your custom needs.
			*/
			$fp = fopen($this->fileName.'.json', 'w');
			fwrite($fp, $data);
			fclose($fp);
		}
	}

	protected function parseFolders($url, $foldername)
	{
		if ($this->checkString($url))
		{
			// get the page html
			if (($html = @file_get_contents($url)) !== FALSE)
			{
				//Create a new DOM document
				$dom = new DOMDocument;

				//Parse the HTML. The @ is used to suppress any parsing errors
				//that will be thrown if the $html string isn't valid XHTML.
				@$dom->loadHTML($html);

				//Get all links. You could also use any other tag name here,
				//like 'img' or 'table', to extract other tags.
				$links = $dom->getElementsByTagName('a');

				//Iterate over the extracted links and display their URLs
				if ($this->checkObject($links))
				{
					// the types
					$types = array('folders','files');
					foreach ($types as $type)
					{
						// folder bucket
						$buket = array();
						foreach ($links as $nr => $link)
						{
							// switch to add link
							$add = false;
							// get actual link
							$href = $link->getAttribute('href');
							// only use link reated to type
							if ($this->checkArray($this->{'get'.$type}) && $this->checkString($href))
							{
								foreach ($this->{'get'.$type} as $get)
								{
									if (!$add)
									{
										if (strpos($get,'?dl') === false)
										{
											$get = $get . '?dl=0';
										}
										if (strpos($href,'https://www.dropbox.com/') !== false  && strpos($href,$get) !== false)
										{
											$add = true;
										}
									}
								}
							}
							else
							{
								if (strpos($href,'https://www.dropbox.com/') !== false && strpos($href,'?dl=0') !== false)
								{
									$add = true;
								}
							}

							// remove if not related to type
							if ($this->checkArray($this->{'not'.$type}) && $this->checkString($href) && $add)
							{
								foreach ($this->{'not'.$type} as $not)
								{
									if ($add)
									{
										if (strpos($href,$not) !== false)
										{
											$add = false;
										}
									}
								}
							}

							// now add if still related to type
							if ($add)
							{
								if (!in_array($href,$buket))
								{
									$name = str_replace('?dl=0','',substr($href, strrpos($href, '/') + 1));

									if ($type == 'folders' && strpos($name,'.') === false)
									{
										// parse this folder for more files and sub folders
										$this->parseFolders($href, $foldername.'/'.str_replace('?lst','',$name));
										$buket[] = $href;
										unset($links->$nr);

									}
									elseif ($type == 'files' && strpos($name,'.') !== false)
									{
										// Add to file list
										$this->files[$foldername.'/'.$name] = str_replace('?dl=0','?dl=1',$href);
										$buket[] = $href;
										unset($links->$nr);
									}
								}
							}
						}
					}
				}
				return true;
			}
		}
		return false;
	}

	protected function checkObject($object)
	{
		if (isset($object) && is_object($object) && count($object) > 0)
		{
			return true;
		}
		return false;
	}

	protected function checkArray($array)
	{
		if (isset($array) && is_array($array) && count($array) > 0)
		{
			return true;
		}
		return false;
	}

	protected function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}
}

?>
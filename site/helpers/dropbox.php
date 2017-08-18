<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method
/-------------------------------------------------------------------------------------------------------/

	@version			2.0.0 - 03rd November, 2016
	@package		Dropbox API 2
	@subpackage		dropbox.php
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
	/**
	 * final url
	 */
	protected $url;
	
	/**
	 * The array for the post url
	 */
	protected $postUrl = array( 
		"protocol" => "https://",
		"suddomain" => "api.",
		"domain" => "dropboxapi.com",
		"path" => "/2/"
	);

	/**
	 * the verious pathes we need
	 */	
	protected $domainpath = array( 
		"list_folder" => "files/list_folder",
		"list_folder_continue" => "files/list_folder/continue",
		"create_shared_link" => "sharing/create_shared_link",
		"get_shared_link_metadata" => "sharing/get_shared_link_metadata",
		"revoke" => "auth/token/revoke"
	);

	/**
	 * the target pathe to get
	 */	
	protected $targetPath = false;
	protected $targetPathOriginal = false;

	/**
	 * oauth token
	 */	
	protected $oauthToken;

	/**
	 * the verious pathes we need
	 */	
	protected $permissionType;

	/**
	 * The loop controller in calling Dropbox API
	 */	
	protected $continueCalling = array();

	/**
	 * the success switch
	 */	
	protected $succes;

	/**
	 * the type call
	 */	
	protected $type;

	/**
	 * the query for the call
	 */	
	protected $query;

	/**
	 * the query for the call
	 */	
	protected $model;

	/**
	 * the mediaData bucket
	 */	
	public $mediaData = array();

	/**
	 * the error messages
	 */	
	public $error_summary = array();
	
	/**
	* 	force the update to reset
	**/
	public $forceReset = false;

	/**
	 * Constructor
	 */
	public function __construct(JModelLegacy $model, $buildType)
	{
		// set the url at this point for now
		$this->url = $this->postUrl["protocol"].$this->postUrl["suddomain"].$this->postUrl["domain"].$this->postUrl["path"];
		// set the local model
		$this->model = $model;
		// set the build type
		$this->build = (int) $buildType;
	}

	/**
	 * getFiles
	 * 
	 * =============
	 * $details
	 * =============
	 * sourceID
	 * dropboxOption
	 * dropboxTarget
	 * addTypes
	 * 
	 */
	public function getFiles($token, $permissiontype, $details = array())
	{
		// we need more then the normal time to run this script 5 minutes at least.
		ini_set('max_execution_time', 500);
		// set the oauth toke
		$this->oauthToken = $token;
		
		// set the permission type
		$this->permissionType = $permissiontype;
		
		// set the details
		if ($this->checkArray($details))
		{
			foreach ($details as $detail => $value)
			{
				$this->$detail = $value;
			}
		}
		
		// set the curent folder path
		if (!$this->setFolderPath())
		{
			return false;
		}
		
		// start the main factory that calles for the folder data
		$this->query = array("path" => $this->targetPath, "recursive" => true, "include_media_info" => true);
		$this->type = 'list_folder';
		if ($this->makeCall())
		{
			return true;
		}
	}
	
	public function revokeToken($token = null)
	{
		if ($token)
		{
			// set the oauth toke
			$this->oauthToken = $token;
		}
		// set the call to revoke the token
		$this->query = 'null';
		$this->type = 'revoke';
		if ($this->makeCall())
		{
			return true;
		}
		return false;
	}
	
	protected function setFolderPath()
	{
		if ('full' == $this->permissionType && isset($this->dropboxOption) && isset($this->dropboxTarget) && $this->checkString($this->dropboxTarget))
		{
			if (2 == $this->dropboxOption)
			{
				// simply set the path
				$this->targetPath = '/'.trim(strtolower($this->dropboxTarget), '/');
				
				return true;
			}
			elseif (1 == $this->dropboxOption)
			{
				// make a call to get the path
				$this->query = array("url" => $this->dropboxTarget);
				$this->type = 'get_shared_link_metadata';
				if ($this->makeCall())
				{
					return true;
				}
			}
		}
		elseif ('app' == $this->permissionType)
		{
			$this->targetPath = "";
			
			return true;
		}
		return false;
	}
	
	protected function makeCall()
	{
		if ($this->_isCurl())
		{
			return $this->makeCurlCall();
		}
		else
		{
			return $this->makeGetCall();
		}
	}
	
	protected function makeGetCall()
	{
		
		$options = array(
			'http' => array(
				'header' => "Content-Type: application/json\r\n".
					"Authorization: Bearer ".$this->oauthToken,
				'method'  => "POST"
			),
		);
		
		if ($this->checkArray($this->query))
		{
			$this->query = json_encode($this->query);
		}
		$options['http']['content'] = $this->query;
		
		$context = stream_context_create($options);
		$response = file_get_contents($this->url.$this->domainpath[$this->type], false, $context);


		if ($response === FALSE)
		{
			$this->error_summary[] = $this->type.'_error';
			return false;
		}
		else
		{
			// store the result
			return $this->setTheResult(json_decode($response));
		}
	}
	
	protected function makeCurlCall()
	{
		$headers = array('Authorization: Bearer '. $this->oauthToken,
		    'Content-Type: application/json'
		);
		
		$ch = curl_init($this->url.$this->domainpath[$this->type]);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// check if query is set		
		if ($this->checkArray($this->query))
		{
			$this->query = json_encode($this->query);
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->query);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug
		
		$response = curl_exec($ch);
		
		curl_close($ch);
		
		if ($this->checkJson($response))
		{
			$response = json_decode($response);
		}
		// store the result
		return $this->setTheResult($response);
	}
	
	protected function setTheResult($data)
	{
		// if there was an error stop!!!
		if (isset($data->error_summary))
		{
			$this->error_summary[] = $data->error_summary;
			$this->forceReset = true;
			return false;
		}
		
		// deal with each type
		switch ($this->type)
		{
			case "list_folder":			
			case "list_folder_continue":
				if (isset($data->entries) && $this->checkArray($data->entries))
				{
					if ($this->storeFiles($data->entries))
					{
						// run the continue if needed
						if (isset($data->has_more) && $data->has_more && isset($data->cursor))
						{
							// start the main factory that calles for the folder data
							$this->query = array("cursor" => $data->cursor);
							$this->type = 'list_folder_continue';
							if ($this->makeCall())
							{
								return true;
							}
						}
						return true;
					}
				}
				$this->error_summary[] = $this->type.'_error';
				break;
			case "create_shared_link":
				if (isset($data->url) && isset($data->path) && $this->storeSharedLink($this->fixPath($data->path), str_replace('?dl=0','?dl=1',$data->url)))
				{
					// we stored the link
					return true;
				}
				$this->error_summary[] = $this->type.'_error';
				break;
			case "get_shared_link_metadata":
				if (isset($data->path_lower))
				{
					$this->targetPath = $data->path_lower;
					return true;
				}
				$this->error_summary[]	= $this->type.'_error';
				break;
			case "revoke":
				if (is_null($data))
				{
					return true;
				}
				$this->error_summary[] = $this->type.'_error';
				break;
		}
		$this->forceReset = true;
		return false;
	}
	
	protected function storeSharedLink($path, $url)
	{
		// we need to store the url to DB
		if (isset($this->mediaData[$path]))
		{
			$localListing				= array();
			$localListing['id']			= 0;
			$localListing['name']			= $this->mediaData[$path]['name'];
			$localListing['size']			= $this->mediaData[$path]['size'];
			$localListing['key']			= $path;
			$localListing['url']			= $url;
			$localListing['build']			= $this->build;
			$localListing['external_source']	= (int) $this->sourceID;
			// free some memory
			unset($this->mediaData[$path]);
			// check if item already set
			if ($id = $this->model->searchForId($path))
			{
				// update item
				$localListing['id'] = $id;
			}
			return $this->model->save($localListing);
		}
		return false;
	}

	protected function storeFiles($entries)
	{		
		foreach ($entries as $item)
		{			
			if (isset($item->{'.tag'}) && 'file' == $item->{'.tag'} && isset($item->name))
			{
				$addLink = false;
				// remove if not related to type
				if (isset($this->addTypes) && $this->checkArray($this->addTypes))
				{
					foreach ($this->addTypes as $add)
					{
						if (strpos($item->name,$add) !== false)
						{
							$addLink = true;
						}
					}
				}
				if ($addLink && isset($item->path_lower))
				{
					// store media info
					$this->mediaData[$this->fixPath($item->path_lower)] = array('name' => $item->name, 'size' => $item->size);
					// get the shared link
					$this->query = array("path" => $item->path_lower);
					$this->type = 'create_shared_link';
					if (!$this->makeCall())
					{
						return false;
					}
				}
			}
		}
		return true;
	}
	
	protected function fixPath($path)
	{
		if ($this->checkString($this->targetPath))
		{
			$path = str_replace($this->targetPath, 'VDM_pLeK_h0uEr', $path);
		}
		else
		{
			$path = 'VDM_pLeK_h0uEr'.$path;
		}
		return $path;
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
	
	protected function checkJson($string)
	{
		if ($this->checkString($string))
		{
			json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
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
	
	protected function _isCurl()
	{
		return function_exists('curl_version');
	}
}

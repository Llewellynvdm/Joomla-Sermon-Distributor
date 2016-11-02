<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		1.3.4
	@build			31st October, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		download.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Sermondistributor Download Controller
 */
class SermondistributorControllerDownload extends JControllerLegacy
{
	
	public function __construct($config)
	{
		parent::__construct($config);
		// load the tasks 
		$this->registerTask('file', 'download');
	}
	
	public function download()
	{
		$jinput 	= JFactory::getApplication()->input;
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($token == $call_token)
                {
			$task = $this->getTask();
			switch($task)
                        {
				case 'file':
					$keys = $jinput->get('key', NULL, 'BASE64');
					$enUrl = $jinput->get('link', NULL, 'BASE64');
					$filename = $jinput->get('filename', NULL, 'CMD');
					if((base64_encode(base64_decode($enUrl, true)) === $enUrl) && (base64_encode(base64_decode($keys, true)) === $keys) && $filename)
					{
						// we must first count this download
						if (SermondistributorHelper::countDownload($keys,$filename))
						{
							// get Site name
							$config = JFactory::getConfig();
							$vendor = $config->get('sitename');
							$name = SermondistributorHelper::safeString($filename, 'Ww');
							// Get local key
							$localkey = SermondistributorHelper::getLocalKey();
							$opener = new FOFEncryptAes($localkey, 128);
							$link = rtrim($opener->decryptString(base64_decode($enUrl)));
							$info = $this->getContentInfo($link);
							// set headers
							$app = JFactory::getApplication();
							$app->setHeader('Accept-ranges', 'bytes', true);
							$app->setHeader('Connection', 'keep-alive', true);
							$app->setHeader('Content-Encoding', 'none', true);
							$app->setHeader('Content-disposition', 'attachment; filename="'.$filename.'";', true);
							if (isset($info['type']) && $info['type'])
							{
								$app->setHeader('Content-Type', $info['type'], true);
							}
							elseif (strpos($filename, '.mp3') !== false)
							{
								$app->setHeader('Content-Type', 'audio/mpeg', true);
							}
							else
							{
								$app->setHeader('Content-Type', 'application/octet-stream', true);
							}
							// important to have the file size.
							if (isset($info['filesize']) && $info['filesize'])
							{
								$app->setHeader('Content-Length', (int) $info['filesize'], true);
								$app->setHeader('Content-Size', (int) $info['filesize'], true);	
							}
							$app->setHeader('Content-security-policy', 'referrer no-referrer', true);
							$app->setHeader('Content-Name', '"'.$name.'"', true);
							$app->setHeader('Content-Version', '1.0', true);
							$app->setHeader('Content-Vendor', '"'.$vendor.'"', true);
							$app->setHeader('Content-URL', '"'.JUri::getInstance().'"', true);							
							$app->setHeader('etag', md5($enUrl), true);
							$app->setHeader('Pragma', 'public', true);
							$app->setHeader('cache-control', 'max-age=0', true);
							$app->setHeader('x-robots-tag', 'noindex, nofollow, noimageindex', true);
							$app->setHeader('x-content-security-policy', 'referrer no-referrer', true);
							$app->setHeader('x-webkit-csp', 'referrer no-referrer', true);
							$app->setHeader('x-content-security-policy', 'referrer no-referrer', true);
							// get the file
							readfile($link); 
							$app->sendHeaders();
							$app->close();
							jexit();
						}
					}
				break;
			}
		}
		return false;
	}
	
	protected function getContentInfo($url)
	{
		// we first try the curl option
		if ($this->_isCurl())
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$data = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			// then we try getheaders (this is slower)
			stream_context_set_default( array('http' => array('method' => 'HEAD')));
			$headers = get_headers($url);
			if (SermondistributorHelper::checkArray($headers))
			{
				$data = implode("\n", $headers);
			}
		}
		// get the Content Length
		if (preg_match('/Content-Length: (\d+)/', $data, $matches))
		{
			// Contains file size in bytes
			$found['filesize'] = (int) $matches[1];

		}
		// get the Content Type
		if (preg_match_all('/Content-Type: (.+)/', $data, $matches))
		{
			foreach ($matches[1] as $match)
			{
				// not the html
				if (strpos( $match, 'text/html') === false)
				{
					$found['type'] =  $match;
					break;
				}
			}

		}
		// return found values
		if (isset($found) && SermondistributorHelper::checkArray($found))
		{
			return $found;
		}
		return false;
	}
	
	protected function _isCurl()
	{
		return function_exists('curl_version');
	}
}

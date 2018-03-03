<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method
/-------------------------------------------------------------------------------------------------------/

	@version		2.0.3 - 27th January, 2018
	@package		Dropbox API 2
	@subpackage		dropbox.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

/**
 * Dropbox class to get shared links
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
		"list_shared_links" => "sharing/list_shared_links",
		"list_folder" => "files/list_folder",
		"list_folder_continue" => "files/list_folder/continue",
		"create_shared_link_with_settings" => "sharing/create_shared_link_with_settings",
		"get_shared_link_metadata" => "sharing/get_shared_link_metadata",
		"revoke" => "auth/token/revoke"
	);

	/**
	 * the target path to get
	 */	
	protected $targetPath = false;
	protected $targetPathOriginal = false;

	/**
	 * oauth token
	 */	
	protected $oauthToken = array();
	protected $token = 0;

	/**
	 * the verious pathes we need
	 */	
	protected $permissionType;

	/**
	 * The array of queries for creating shared links
	 */	
	protected $createSharedLinks = array();
	protected $createSharedLinks_ = array(); // retry array

	/**
	 * The array of queries for getting shared links
	 */	
	protected $getSharedLinks = array();
	protected $getSharedLinks_ = array(); // retry array

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
	 * the slow down timer
	 */	
	protected $slowdown = 0;

	/**
	 * the speed up switch
	 */	
	protected $speedup = false;

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
		// set the first call
		$this->firstCall = 'get'; // TODO (we may what to make this dynamic)
		// set the second call
		$this->secondCall = 'create'; // TODO (we may what to make this dynamic)
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
		// little tweak for big folders (use multy tokens)
		if (strpos($token, '____') !== false)
		{
			$this->oauthToken = array_filter((array) explode('____', $token),
				function ($string) {
					return $this->checkString($string);
				});
		}
		else
		{
			// set the oauth toke
			$this->oauthToken = array($token);
		}
		
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
			if ($this->_isCurl())
			{
				// run the share link builder
				return $this->makeMultiExecCalls();
			}
			return true;
		}
		return false;
	}
	
	protected function getToken()
	{
		$key = $this->token;
		if (isset($this->oauthToken[$key]))
		{
			// update key
			$this->token++;
			return $this->oauthToken[$key];
		}
		// reset key
		$this->token = 1;
		// return first token
		return $this->oauthToken[0];
	}
	
	protected function makeMultiExecCalls()
	{
		$this->succesCall = true;
		// make the fist call
		$this->multiSharedLinks($this->firstCall);
		// make the second call
		$this->multiSharedLinks($this->secondCall);
		// slow down if needed
		$this->speedup = false;
		// for safety
		if (($this->checkArray($this->createSharedLinks) || $this->checkArray($this->createSharedLinks_) ||
			$this->checkArray($this->getSharedLinks) || $this->checkArray($this->getSharedLinks_)) &&
			$this->succesCall)
		{
			return $this->makeMultiExecCalls();
		}
		// all done
		return $this->succesCall;
	}
	
	protected function multiSharedLinks($type)
	{
		switch ($type)
		{
			case "create":
				// great links if not made already
				if ($this->checkArray($this->createSharedLinks) || $this->checkArray($this->createSharedLinks_))
				{
					$url = $this->url.$this->domainpath['create_shared_link_with_settings'];
					$this->type = 'create_shared_link_with_settings';
					// build return function
					$storeSharedLink = function ($data, $target) {
						// check if we are going to fast
						if (isset($data->error_summary) && strpos($data->error_summary, 'too_many_requests') !== false)
						{
							$this->slowDown($data);
							// this call was droped, so we must try again
							$this->createSharedLinks_[] = json_encode(array("path" => $target, "settings" => array("requested_visibility" => "public")));
						}
						// check if link already made
						elseif (isset($data->error_summary) && strpos($data->error_summary, 'shared_link_already_exists') !== false)
						{
							$this->getSharedLinks[] = json_encode(array("path" => $target));
						}
						else
						{
							$this->curlCallResult($data);
						}
					};
					// run call
					if ($this->checkArray($this->createSharedLinks))
					{
						$this->multiCall($url, 'createSharedLinks', $storeSharedLink);
					}
					// also run the retries
					if ($this->checkArray($this->createSharedLinks_))
					{
						$this->multiCall($url, 'createSharedLinks_', $storeSharedLink);
					}
				}
			break;
			case "get":
				// now get the already made links
				if ($this->checkArray($this->getSharedLinks) || $this->checkArray($this->getSharedLinks_))
				{
					$url = $this->url.$this->domainpath['list_shared_links'];
					$this->type = 'list_shared_links';
					// build return function
					$storeSharedLink = function ($data, $target) {
						// check if link not made
						if (isset($data->error_summary) && strpos($data->error_summary, 'too_many_requests') !== false)
						{
							$this->slowDown($data);
							// this call was droped, so we must try again
							$this->getSharedLinks_[] = json_encode(array("path" => $target));
						}
						elseif (isset($data->error_summary))
						{
							$this->createSharedLinks[] = json_encode(array("path" => $target, "settings" => array("requested_visibility" => "public")));
						}
						else
						{
							$this->curlCallResult($data);
						}
					};
					// run call
					if ($this->checkArray($this->getSharedLinks))
					{
						$this->multiCall($url, 'getSharedLinks', $storeSharedLink);
					}
					// also run the retries
					if ($this->checkArray($this->getSharedLinks_))
					{
						$this->multiCall($url, 'getSharedLinks_', $storeSharedLink);
					}
				}
			break;
		}
	}
	
	protected function multiCall(&$url, $type, &$storeSharedLink)
	{
		$timer = 1;
		$options = array();
		// set the options array and make the calls every 550
		foreach ($this->{$type} as $query)
		{
			$options[] = array(CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $this->getToken(), 'Content-Type: application/json'), CURLOPT_POST => 1,  CURLOPT_POSTFIELDS => $query); $timer++;
			// check timer
			if ($timer >= 550)
			{
				// run multi curl
				$this->curlMultiExec($url, $options, $storeSharedLink);
				// reset
				$timer = 1;
				$options = array();
			}
		}
		// make sure all was done
		if ($timer > 1 && $this->checkArray($options))
		{
			// run multi curl
			$this->curlMultiExec($url, $options, $storeSharedLink);
		}
		// reset the values
		$this->{$type} = array();
		// check if we have errors
		if ($this->checkArray($this->error_summary))
		{
			$this->succesCall = false;
		}
	}
	
	public function slowDown($data)
	{
		if (!$this->speedup)
		{
			// set default
			$this->slowdown = 300000000;
			// can we set it dynamicly
			if (isset($data->error) && isset($data->error->retry_after))
			{
				// convert retry_after minutes to microseconds
				$this->slowdown = (int) bcmul($data->error->retry_after, 1000000);
			}
		}
	}
	
	public function revokeToken($token = null)
	{
		if ($token)
		{
			// set the oauth toke
			$this->oauthToken[] = $token;
		}
		// set the call to revoke the token
		$this->query = 'null';
		$this->type = 'revoke';
		// remove all tokens
		$removed = true;
		if ($this->checkArray($this->oauthToken))
		{
			foreach($this->oauthToken as $token)
			{
				if (!$this->makeCall())
				{
					$removed = false;
				}
			}
			return $removed;
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
					"Authorization: Bearer ".$this->getToken(),
				'method'  => "POST"
			),
		);
		
		if ($this->checkArray($this->query))
		{
			$this->query = json_encode($this->query);
		}
		// add the query
		if ($this->checkString($this->query))
		{
			$options['http']['content'] = $this->query;
		}
		$context = stream_context_create($options);
		$response = file_get_contents($this->url.$this->domainpath[$this->type], false, $context);

		// store the result
		return $this->getCallResult($response);
	}
	
	protected function getCallResult($response)
	{
		if ($response === FALSE)
		{
			$this->error_summary[] = $this->type.'_error';
			return false;
		}
		// store the result
		return $this->setTheResult(json_decode($response));
	}
	
	protected function makeCurlCall()
	{
		// do not run creat shared link this way
		$headers = array('Authorization: Bearer '. $this->getToken(),
				'Content-Type: application/json'
			);

		$ch = curl_init($this->url.$this->domainpath[$this->type]);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// check if query is set		
		if ($this->checkArray($this->query))
		{
			$this->query = json_encode($this->query);
		}
		// add the query
		if ($this->checkString($this->query))
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->query);
		}
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

		$response = curl_exec($ch);

		curl_close($ch);

		// store the result
		return $this->curlCallResult($response);
	}
	
	public function curlCallResult($response)
	{		
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
			case "list_shared_links":
				if (isset($data->links) && $this->checkArray($data->links))
				{
					foreach ($data->links as $link)
					{
						if (!$this->storeSharedLink($link))
						{
							// we could not stored the link
							return false;
						}
					}
					return true;
				}
				$this->error_summary[] = $this->type.'_error';
				break;
			case "create_shared_link_with_settings":
				if ($this->storeSharedLink($data))
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
	
	protected function storeSharedLink($data)
	{
		// we need to store the url to DB
		if (isset($data->url) && isset($data->name) && isset($data->size) && (isset($data->path) || isset($data->path_lower)))
		{
			$path								= (isset($data->path)) ? $data->path : $data->path_lower;
			$localListing						= array();
			$localListing['id']					= 0;
			$localListing['name']				= $data->name;
			$localListing['size']				= $data->size;
			$localListing['key']				= $this->fixPath($path);
			$localListing['url']				= str_replace('?dl=0','?dl=1',$data->url);
			$localListing['build']				= $this->build;
			$localListing['external_source']	= (int) $this->sourceID;
			// check if item already set
			if ($id = $this->model->searchForId($localListing['key']))
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
					// set based on first call
					if ('get' === $this->firstCall)
					{
						// first check if shared link exist
						$this->query = array("path" => $item->path_lower);
						// set the type of call
						$this->type = 'list_shared_links';
					}
					else
					{
						// first check if shared link exist
						$this->query = array("path" => $item->path_lower, "settings" => array("requested_visibility" => "public"));
						// set the type of call
						$this->type = 'create_shared_link_with_settings';
					}
					// if we have curl the use multi curl execution
					if ($this->_isCurl())
					{
						// set query to worker
						$this->{$this->firstCall."SharedLinks"}[] = json_encode($this->query);
					}
					elseif (!$this->makeCall())
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
	
	protected function curlMultiExec(&$url, &$_options, $callback = null)
	{
		if ($this->checkString($url))
		{
			// check if we should slow down
			if ($this->slowdown > 0)
			{
				usleep($this->slowdown);
				// reset the trafic speed
				$this->slowdown = 0;
				$this->speedup = true; // we should only sleep once per/bunch
			}
			// make sure the thread size isn't greater than the # of _options
			$threadSize = 50;
			$threadSize = (sizeof($_options) < $threadSize) ? sizeof($_options) : $threadSize;
			// set the options
			$options = array();
			$options[CURLOPT_URL] = $url;
			$options[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12';
			$options[CURLOPT_RETURNTRANSFER] = TRUE;
			$options[CURLOPT_SSL_VERIFYPEER] = FALSE;
			// start multi threading :)
			$handle = curl_multi_init();
			// start the first batch of requests
			for ($i = 0; $i < $threadSize; $i++)
			{
				if (isset($_options[$i]))
				{
					$ch = curl_init();
					foreach ($_options[$i] as $curlopt => $string)
					{
						$options[$curlopt] = $string;
					}
					curl_setopt_array($ch, $options);
					curl_multi_add_handle($handle, $ch);
				}
			}
			// we wait for all the calls to finish (should not take long)
			do {
				while(($execrun = curl_multi_exec($handle, $working)) == CURLM_CALL_MULTI_PERFORM);
					if($execrun != CURLM_OK)
						break;
				// a request was just completed -- find out which one
				while($done = curl_multi_info_read($handle))
				{
					if (is_callable($callback))
					{
						// $info = curl_getinfo($done['handle']);
						// request successful. process output using the callback function.
						$output = curl_multi_getcontent($done['handle']);
						if ($this->checkJson($output) && isset($_options[$i]))
						{
							$callback(json_decode($output), json_decode(end($_options[$i]))->path);
						}
					}
					// check if we should slow down
					if ($this->slowdown > 0)
					{
						usleep($this->slowdown);
						// reset the trafic speed
						$this->slowdown = 0;
						$this->speedup = true; // we should only sleep once per/bunch
					}
					// next key
					$key = $i + 1;
					if(isset($_options[$key]))
					{
						// start a new request (it's important to do this before removing the old one)
						$ch = curl_init(); $i++;
						// add options
						foreach ($_options[$key] as $curlopt => $string)
						{
							$options[$curlopt] = $string;
						}
						curl_setopt_array($ch, $options);
						curl_multi_add_handle($handle, $ch);
						// remove options again
						foreach ($_options[$key] as $curlopt => $string)
						{
							unset($options[$curlopt]);
						}
					}
					// remove the curl handle that just completed
					curl_multi_remove_handle($handle, $done['handle']);
				}
				// stop wasting CPU cycles and rest for a couple ms
				usleep(10000);
			} while ($working);
			// close the curl multi thread
			curl_multi_close($handle);
			// okay done
			return true;
		}
		return false;
	}
}

<?php

/***[JCBGUI.power.licensing_template.781.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2022
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Abstraction;



/***[JCBGUI.power.head.781.$$$$]***/
use Joomla\CMS\Factory;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\PHPConfigurationCheckerInterface;
use JoomSermon\Joomla\Abstraction\Registry;


/**
 * PHP Configuration Checker
 * 
 * @since 5.0.2
 */
abstract class PHPConfigurationChecker extends Registry implements PHPConfigurationCheckerInterface
{

/***[JCBGUI.power.main_class_code.781.$$$$]***/
	/**
	 * The upload max filesize value
	 *
	 * @var    string
	 * @since  5.0.2
	 **/
	protected  string $upload_max_filesize;

	/**
	 * The post max size value
	 *
	 * @var    string
	 * @since  5.0.2
	 **/
	protected  string $post_max_size;

	/**
	 * The max execution time value
	 *
	 * @var    int
	 * @since  5.0.2
	 **/
	protected  int $max_execution_time;

	/**
	 * The max input vars value
	 *
	 * @var    int
	 * @since  5.0.2
	 **/
	protected  int $max_input_vars;

	/**
	 * The max input time value
	 *
	 * @var    int
	 * @since  5.0.2
	 **/
	protected  int $max_input_time;

	/**
	 * The memory limit value
	 *
	 * @var    string
	 * @since  5.0.2
	 **/
	protected  string $memory_limit;

	/**
	 * The registry array.
	 *
	 * @var    array
	 * @since 5.0.2
	 **/
	protected array $active = [
		'php' => [
			'upload_max_filesize' => [
				'success' => 'The upload_max_filesize is appropriately set to handle large files, which is essential for uploading substantial components and media.',
				'warning' => 'The current upload_max_filesize may not support large file uploads effectively, potentially causing failures during component installation.'
			],
			'post_max_size' => [
				'success' => 'The post_max_size setting is sufficient to manage large data submissions, ensuring smooth data processing within forms and uploads.',
				'warning' => 'An insufficient post_max_size can lead to truncated data submissions, affecting form functionality and data integrity.'
			],
			'max_execution_time' => [
				'success' => 'Max execution time is set high enough to execute complex operations without premature termination, which is crucial for lengthy operations.',
				'warning' => 'A low max execution time could lead to script timeouts, especially during intensive operations, which might interrupt execution and cause failures during the compiling of a large extension.'
			],
			'max_input_vars' => [
				'success' => 'The max_input_vars setting supports a high number of input variables, facilitating complex forms and detailed component configurations.',
				'warning' => 'Too few max_input_vars may result in lost data during processing complex forms, which can lead to incomplete configurations and operational issues.'
			],
			'max_input_time' => [
				'success' => 'Max input time is adequate for processing inputs efficiently during high-load operations, ensuring no premature timeouts.',
				'warning' => 'An insufficient max input time could result in incomplete data processing during input-heavy operations, potentially leading to errors and data loss.'
			],
			'memory_limit' => [
				'success' => 'The memory limit is set high to accommodate extensive operations and data processing, which enhances overall performance and stability.',
				'warning' => 'A low memory limit can lead to frequent crashes and performance issues, particularly when processing large amounts of data or complex calculations.'
			]
		],
		'environment' => [
			'name' => 'extension environment',
			'objective' => 'These settings are crucial for ensuring the successful installation and stable functionality of the extension.',
			'wiki_name' => 'PHP Settings Wiki',
			'wiki_url' => '#'
		]
	];

	/**
	 * Application object.
	 *
	 * @since  5.0.2
	 **/
	protected  $app;

	/**
	 * Constructor.
	 *
	 * @param       $app      The app object.
	 *
	 * @since  5.0.2
	 */
	public function __construct($app = null)
	{
		$this->app = $app ?: Factory::getApplication();

		// set the required PHP Configures
		$this->set('php.upload_max_filesize.value', $this->upload_max_filesize);
		$this->set('php.post_max_size.value', $this->post_max_size);
		$this->set('php.max_execution_time.value', $this->max_execution_time);
		$this->set('php.max_input_vars.value', $this->max_input_vars);
		$this->set('php.max_input_time.value', $this->max_input_time);
		$this->set('php.memory_limit.value', $this->memory_limit);
	}

	/**
	 * Check that the required configurations are set for PHP
	 *
	 * @return void
	 * @since  5.0.2
	 **/
	public function run(): void
	{
		$showHelp = false;

		// Check each configuration and provide detailed feedback
		$configurations = $this->active['php'] ?? [];
		foreach ($configurations as $configName => $configDetails)
		{
			$currentValue = ini_get($configName);
			if ($currentValue === false)
			{
				$this->app->enqueueMessage("Error: Unable to retrieve current setting for '{$configName}'.", 'error');
				continue;
			}

			$requiredValue = $configDetails['value'] ?? 0;
			$isMemoryValue = strpbrk($requiredValue, 'KMG') !== false;

			$requiredValueBytes = $isMemoryValue ? $this->convertToBytes($requiredValue) : (int) $requiredValue;
			$currentValueBytes = $isMemoryValue ? $this->convertToBytes($currentValue) : (int) $currentValue;
			$conditionMet = $currentValueBytes >= $requiredValueBytes;

			$messageType = $conditionMet ? 'message' : 'warning';
			$messageText = $conditionMet ?
				"Success: {$configName} is set to {$currentValue}. " . $configDetails['success'] ?? '':
				"Warning: {$configName} configuration should be at least {$requiredValue} but is currently {$currentValue}. " . $configDetails['warning'] ?? '';

			$showHelp = ($showHelp || $messageType === 'warning') ? true : false;

			$this->app->enqueueMessage($messageText, $messageType);
		}

		if ($showHelp)
		{
			$this->app->enqueueMessage("To optimize your {$this->get('environment.name', 'extension')}, specific PHP settings must be enhanced.<br>{$this->get('environment.objective', '')}<br>We've identified that certain configurations currently do not meet the recommended standards.<br>To adjust these settings and prevent potential issues, please consult our detailed guide available at <a href=\"https://{$this->get('environment.wiki_url', '#')}\" target=\"_blank\">{$this->get('environment.wiki_name', 'PHP Settings Wiki')}</a>.", 'notice');
		}
	}

	/**
	 * Helper function to convert PHP INI memory values to bytes
	 *
	 * @param  string  $value     The value to convert
	 *
	 * @return int     The bytes value
	 * @since  5.0.2
	 */
	protected function convertToBytes(string $value): int
	{
		$value = trim($value);
		$lastChar = strtolower($value[strlen($value) - 1]);
		$numValue = substr($value, 0, -1);

		switch ($lastChar)
		{
			case 'g':
				return $numValue * 1024 * 1024 * 1024;
			case 'm':
				return $numValue * 1024 * 1024;
			case 'k':
				return $numValue * 1024;
			default:
				return (int) $value;
		}
	}/***[/JCBGUI$$$$]***/

}


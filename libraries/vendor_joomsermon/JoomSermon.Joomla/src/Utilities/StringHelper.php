<?php

/***[JCBGUI.power.licensing_template.12.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Utilities;



/***[JCBGUI.power.head.12.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\Filter\InputFilter;
use Joomla\CMS\Language\LanguageFactoryInterface;
use Joomla\CMS\Language\LanguageFactory;
use Joomla\CMS\Language\Language;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\Component\Helper;


/**
 * Some string tricks
 * 
 * @since  3.0.9
 */
abstract class StringHelper
{

/***[JCBGUI.power.main_class_code.12.$$$$]***/
	/**
	 * The Main Active Language
	 * 
	 * @var    string
	 * @since  3.0.9
	 */
	public static $langTag;

	/**
	 * Validate that input is a non-empty, non-whitespace-only string.
	 *
	 * @param mixed $input The input value to validate.
	 *
	 * @returns bool  True if input is a non-empty, non-whitespace-only string, otherwise false.
	 * @since  3.0.9
	 */
	public static function check($input): bool
	{
		return is_string($input) && trim($input) !== '';
	}

	/**
	 * Shortens a string to a specified length, optionally adding a tooltip with the full text.
	 *
	 * This method safely shortens the input string without cutting words abruptly. If the string
	 * exceeds the specified length, ellipses (...) are added. Optionally, a tooltip containing the
	 * longer original string can be included.
	 *
	 * @param mixed $string   The string you would like to shorten.
	 * @param int   $length   The maximum length for the shortened string. Default is 40.
	 * @param bool  $addTip   Whether to add a tooltip with the original longer string. Default true.
	 *
	 * @return string|mixed   The shortened string, optionally with a tooltip. Or original value passed
	 * @since  3.2.1
	 */
	public static function shorten($string, int $length = 40, bool $addTip = true)
	{
		// Validate string input and return original if invalid or short enough.
		if (!self::check($string) || mb_strlen($string) <= $length)
		{
			return $string;
		}

		// Truncate string to nearest word boundary
		$shortened = mb_substr($string, 0, $length);

		// Find the last space to avoid cutting off a word
		$lastSpace = mb_strrpos($shortened, ' ');
		if ($lastSpace !== false)
		{
			$shortened = mb_substr($shortened, 0, $lastSpace);
		}

		// Prepare trimmed and shortened output with ellipses
		$shortened = trim($shortened) . '...';

		// Add tooltip if requested
		if ($addTip)
		{
			// Safely escape output for HTML
			$title = self::shorten($string, 400 , false);

			return sprintf(
				'<span class="hasTip" title="%s" style="cursor:help">%s</span>',
				htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($shortened, ENT_QUOTES, 'UTF-8')
			);
		}

		// Return shortened version without tooltip
		return $shortened;
	}

	/**
	 * Makes a string safe by sanitizing and formatting it according to the specified type.
	 *
	 * This method can remove unwanted characters, transliterate text, replace numbers with 
	 * their English equivalents, and apply different case formatting styles.
	 *
	 * @param string  $string            The string to sanitize and format.
	 * @param string  $type              The formatting type to apply. Supported values:
	 *                                   - 'filename'  : Removes special characters and extra spaces.
	 *                                   - 'L'         : Converts to lowercase with underscores replacing spaces.
	 *                                   - 'strtolower': Alias for 'L'.
	 *                                   - 'W'         : Capitalizes the first letter of each word.
	 *                                   - 'w'         : Converts to lowercase (spaces remain).
	 *                                   - 'word'      : Alias for 'w'.
	 *                                   - 'Ww'        : Capitalizes only the first word.
	 *                                   - 'Word'      : Alias for 'Ww'.
	 *                                   - 'WW'        : Converts the entire string to uppercase.
	 *                                   - 'WORD'      : Alias for 'WW'.
	 *                                   - 'U'         : Converts to uppercase with underscores replacing spaces.
	 *                                   - 'strtoupper': Alias for 'U'.
	 *                                   - 'F'         : Capitalizes only the first letter of the entire string.
	 *                                   - 'ucfirst'   : Alias for 'F'.
	 *                                   - 'cA'        : Converts to camelCase.
	 *                                   - 'cAmel'     : Alias for 'cA'.
	 *                                   - 'camelcase' : Alias for 'cA'.
	 * @param string  $spacer            The character to replace spaces with (default: '_').
	 * @param bool    $replaceNumbers    Whether to replace numbers with their English text equivalents (default: true).
	 * @param bool    $keepOnlyCharacters Whether to remove all non-alphabetic characters (default: true).
	 *
	 * @return string The sanitized and formatted string.
	 * @since  3.0.9
	 */
	public static function safe($string, string $type = 'L', string $spacer = '_', bool $replaceNumbers = true, bool $keepOnlyCharacters = true): string
	{
		if ($replaceNumbers)
		{
			// remove all numbers and replace with English text version (works well only up to millions)
			$string = self::numbers($string);
		}

		// Only continue if we have a string
		if (!self::check($string))
		{
			// not a string
			return '';
		}

		// create file name without the extension that is safe
		if ($type === 'filename')
		{
			// make sure VDM is not in the string
			$string = str_replace('VDM', 'vDm', (string) $string);
			// Remove anything which isn't a word, whitespace, number
			// or any of the following caracters -_()
			// If you don't need to handle multi-byte characters
			// you can use preg_replace rather than mb_ereg_replace
			// Thanks @Łukasz Rysiak!
			// $string = mb_ereg_replace("([^\w\s\d\-_\(\)])", '', $string);
			$string = preg_replace("([^\w\s\d\-_\(\)])", '', $string);

			// http://stackoverflow.com/a/2021729/1429677
			return preg_replace('/\s+/', ' ', (string) $string);
		}
		// remove all other characters
		$string = trim((string) $string);
		$string = preg_replace('/'.$spacer.'+/', ' ', $string);
		$string = preg_replace('/\s+/', ' ', $string);
		// Transliterate string
		$string = self::transliterate($string);
		// remove all and keep only characters
		if ($keepOnlyCharacters)
		{
			$string = preg_replace("/[^A-Za-z ]/", '', (string) $string);
		}
		// keep both numbers and characters
		else
		{
			$string = preg_replace("/[^A-Za-z0-9 ]/", '', (string) $string);
		}
		// select final adaptations
		if ($type === 'L' || $type === 'strtolower')
		{
			// replace white space with underscore
			$string = preg_replace('/\s+/', (string) $spacer, (string) $string);
			// default is to return lower
			return strtolower($string);
		}
		elseif ($type === 'W')
		{
			// return a string with all first letter of each word uppercase(no underscore)
			return ucwords(strtolower($string));
		}
		elseif ($type === 'w' || $type === 'word')
		{
			// return a string with all lowercase(no underscore)
			return strtolower($string);
		}
		elseif ($type === 'Ww' || $type === 'Word')
		{
			// return a string with first letter of the first word uppercase and all the rest lowercase(no underscore)
			return ucfirst(strtolower($string));
		}
		elseif ($type === 'WW' || $type === 'WORD')
		{
			// return a string with all the uppercase(no underscore)
			return strtoupper($string);
		}
		elseif ($type === 'U' || $type === 'strtoupper')
		{
				// replace white space with underscore
				$string = preg_replace('/\s+/', (string) $spacer, $string);
				// return all upper
				return strtoupper($string);
		}
		elseif ($type === 'F' || $type === 'ucfirst')
		{
				// replace white space with underscore
				$string = preg_replace('/\s+/', (string) $spacer, $string);
				// return with first character to upper
				return ucfirst(strtolower($string));
		}
		elseif ($type === 'cA' || $type === 'cAmel' || $type === 'camelcase')
		{
			// convert all words to first letter uppercase
			$string = ucwords(strtolower($string));
			// remove white space
			$string = preg_replace('/\s+/', '', $string);
			// now return first letter lowercase
			return lcfirst($string);
		}
		// return string
		return $string;
	}

	/**
	 * Convert none English strings to code usable string
	 *
	 * @input  $string  an string
	 *
	 * @returns string
	 * @since   3.0.9
	 */
	public static function transliterate($string): string
	{
		// set tag only once
		if (!self::check(self::$langTag))
		{
			// get global value
			self::$langTag = Helper::getParams()->get('language', 'en-GB');
		}

		// Transliterate on the language requested
		$lang = null;
		if (method_exists(Factory::class, 'getContainer') && Factory::getContainer()->has(LanguageFactoryInterface::class))
		{
			/** @var $langFactory LanguageFactory **/
			$langFactory = Factory::getContainer()->get(LanguageFactoryInterface::class);

			/** @var $lang Language **/
			$lang = $langFactory->createLanguage(self::$langTag);
		}
		elseif (method_exists(Language::class, 'getInstance'))
		{
			/** @var $lang Language **/
			$lang = Language::getInstance(self::$langTag);
		}

		if ($lang !== null)
		{
			return $lang->transliterate($string);
		}

		return $string;
	}

	/**
	 * Ensures a string is safe for HTML output by encoding entities and applying an input filter.
	 *
	 * This method sanitizes the input string, converting special characters to HTML entities 
	 * and applying Joomla's `InputFilter` to remove potentially unsafe HTML.
	 * Optionally, it can also shorten the string while preserving word integrity.
	 *
	 * @param string  $var      The input string containing HTML content.
	 * @param string  $charset  The character set to use for encoding (default: 'UTF-8').
	 * @param bool    $shorten  Whether to shorten the string to a specified length (default: false).
	 * @param int     $length   The maximum length for shortening, if enabled (default: 40).
	 * @param bool    $addTip   Whether to append a tooltip (ellipsis) when shortening (default: true).
	 *
	 * @return string The sanitized and optionally shortened HTML-safe string.
	 * @since 3.0.9
	 */
	public static function html($var, $charset = 'UTF-8', $shorten = false, $length = 40, $addTip = true): string
	{
		if (self::check($var))
		{
			$filter = new InputFilter();
			$string = $filter->clean(
				html_entity_decode(
					htmlentities(
						(string) $var,
						ENT_COMPAT,
						$charset
					)
				),
				'HTML'
			);
			if ($shorten)
			{
				return self::shorten($string, $length, $addTip);
			}
			return $string;
		}
		else
		{
			return '';
		}
	}

	/**
	 * Convert all int in a string to an English word string
	 *
	 * @input    $string  an string with numbers
	 *
	 * @returns  string|null
	 * @since  3.0.9
	 */
	public static function numbers($string): ?string
	{
		// set numbers array
		$numbers = [];
		$search_replace= [];

		// first get all numbers
		preg_match_all('!\d+!', (string) $string, $numbers);

		// check if we have any numbers
		if (isset($numbers[0]) && ArrayHelper::check($numbers[0]))
		{
			foreach ($numbers[0] as $number)
			{
				$search_replace[$number] = self::number((int)$number);
			}

			// now replace numbers in string
			$string = str_replace(array_keys($search_replace), array_values($search_replace), (string) $string);

			// check if we missed any, strange if we did.
			return self::numbers($string);
		}

		// return the string with no numbers remaining.
		return $string;
	}

	/**
	 * Convert an integer into an English word string
	 * Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	 *
	 * @input    $x an int
	 * 
	 * @returns   string
	 * @since  3.0.9
	 */
	public static function number($x)
	{
		$nwords = ["zero", "one", "two", "three", "four", "five", "six", "seven",
			"eight", "nine", "ten", "eleven", "twelve", "thirteen",
			"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
			"nineteen", "twenty", 30 => "thirty", 40 => "forty",
			50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
			90 => "ninety"];

		if(!is_numeric($x))
		{
			$w = $x;
		}
		elseif(fmod($x, 1) != 0)
		{
			$w = $x;
		}
		else
		{
			if($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}
			else
			{
				$w = '';
				// ... now $x is a non-negative integer.
			}

			if($x < 21)   // 0 to 20
			{
				$w .= $nwords[$x];
			}
			elseif($x < 100)  // 21 to 99
			{ 
				$w .= $nwords[10 * floor($x/10)];
				$r = fmod($x, 10);
				if($r > 0)
				{
					$w .= ' ' . $nwords[$r];
				}
			}
			elseif($x < 1000)  // 100 to 999
			{
				$w .= $nwords[floor($x/100)] .' hundred';
				$r = fmod($x, 100);
				if($r > 0)
				{
					$w .= ' and '. self::number($r);
				}
			}
			elseif($x < 1000000)  // 1000 to 999999
			{
				$w .= self::number(floor($x/1000)) .' thousand';
				$r = fmod($x, 1000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::number($r);
				}
			} 
			else //  millions
			{
				$w .= self::number(floor($x/1000000)) .' million';
				$r = fmod($x, 1000000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::number($r);
				}
			}
		}
		return $w;
	}

	/**
	 * Random Key
	 *
	 * @input   int  $size   The size of the random string
	 *
	 * @returns string
	 * @since  3.0.9
	 */
	public static function random(int $size): string
	{
		$bag = "abcefghijknopqrstuwxyzABCDDEFGHIJKLLMMNOPQRSTUVVWXYZabcddefghijkllmmnopqrstuvvwxyzABCEFGHIJKNOPQRSTUWXYZ";
		$key = [];
		$bagsize = strlen($bag) - 1;

		for ($i = 0; $i < $size; $i++)
		{
			$get = rand(0, $bagsize);
			$key[] = $bag[$get];
		}

		return implode($key);
	}/***[/JCBGUI$$$$]***/

}


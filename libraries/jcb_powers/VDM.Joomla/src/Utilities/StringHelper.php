<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace VDM\Joomla\Utilities;


use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Language;
use VDM\Joomla\Utilities\Component\Helper;


/**
 * Some string tricks
 * 
 * @since  3.0.9
 */
abstract class StringHelper
{
	/**
	 * The Main Active Language
	 * 
	 * @var      string
	 * 
	 * @since  3.0.9
	 */
	public static $langTag;

	/**
	 * Check if we have a string with a length
	 *
	 * @input    string  $string The string to check
	 *
	 * @returns bool true on success
	 * 
	 * @since  3.0.9
	 */
	public static function check($string): bool
	{
		return is_string($string) && strlen($string) > 0;
	}

	/**
	 * Shorten a string
	 *
	 * @input	string   The sting that you would like to shorten
	 *
	 * @returns string on success
	 * 
	 * @since  3.2.0
	 */
	public static function shorten($string, $length = 40, $addTip = true)
	{
		if (self::check($string))
		{
			$initial = strlen((string) $string);
			$words = preg_split('/([\s\n\r]+)/', (string) $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$words_count = count((array)$words);

			$word_length = 0;
			$last_word = 0;
			for (; $last_word < $words_count; ++$last_word)
			{
				$word_length += strlen($words[$last_word]);
				if ($word_length > $length)
				{
					break;
				}
			}

			$newString	= implode(array_slice($words, 0, $last_word));
			$final	= strlen($newString);
			if ($initial !== $final && $addTip)
			{
				$title = self::shorten($string, 400 , false);
				return '<span class="hasTip" title="' . $title . '" style="cursor:help">' . trim($newString) . '...</span>';
			}
			elseif ($initial !== $final && !$addTip)
			{
				return trim($newString) . '...';
			}
		}
		return $string;
	}

	/**
	 * Making strings safe (various ways)
	 *
	 * @input	string   The you would like to make safe
	 *
	 * @returns string on success
	 * 
	 * @since  3.0.9
	 */
	public static function safe($string, $type = 'L', $spacer = '_', $replaceNumbers = true, $keepOnlyCharacters = true)
	{
		if ($replaceNumbers === true)
		{
			// remove all numbers and replace with English text version (works well only up to millions)
			$string = self::numbers($string);
		}
		// 0nly continue if we have a string
		if (self::check($string))
		{
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
		// not a string
		return '';
	}

	/**
	 * Convert none English strings to code usable string
	 *
	 * @input	an string
	 *
	 * @returns a string
	 * 
	 * @since  3.0.9
	 */
	public static function transliterate($string)
	{
		// set tag only once
		if (!self::check(self::$langTag))
		{
			// get global value
			self::$langTag = Helper::getParams()->get('language', 'en-GB');
		}

		// Transliterate on the language requested
		$lang = Language::getInstance(self::$langTag);

		return $lang->transliterate($string);
	}

	/**
	 * make sure a string is HTML save
	 *
	 * @input	an html string
	 *
	 * @returns a string
	 * 
	 * @since  3.0.9
	 */
	public static function html($var, $charset = 'UTF-8', $shorten = false, $length = 40, $addTip = true)
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
	 * @input	an string with numbers
	 *
	 * @returns a string
	 * 
	 * @since  3.0.9
	 */
	public static function numbers($string)
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
	 * @input	an int
	 * @returns a string
	 * 
	 * @since  3.0.9
	 */
	public static function number($x)
	{
		$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
			"eight", "nine", "ten", "eleven", "twelve", "thirteen",
			"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
			"nineteen", "twenty", 30 => "thirty", 40 => "forty",
			50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
			90 => "ninety" );

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
	 * @input	 int  $size   The size of the random string
	 *
	 * @returns a string
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
	}

}


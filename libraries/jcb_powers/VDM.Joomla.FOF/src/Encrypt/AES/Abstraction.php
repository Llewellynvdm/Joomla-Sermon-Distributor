<?php
/**
 * @package       FrameworkOnFramework
 * @subpackage    Encryption
 * @copyright     Copyright (C) 2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd. All rights reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @note	      This file has been modified by the Joomla! Project (and VDM) and no longer reflects the original work of its author.
 * @depreciation  This was ported for the sake of those who have stuff encrypted with the FOF encryption suite.
 *                  - Do not use this in new projects.
 *                  - Expect no updates.
 *                  - This is outdated.
 *                  - Not best choice for encryption.
 *                  - Use phpseclib/phpseclib version 3 Instead.
 *                  - Checkout the JCB Crypt Suite. <https://git.vdm.dev/joomla/phpseclib>
 */
namespace VDM\Joomla\FOF\Encrypt\AES;


/**
 * Abstract AES encryption class
 * 
 * @package  FrameworkOnFramework
 * @since    1.0
 * @deprecated Use phpseclib/phpseclib version 3 Instead. 
 */
abstract class Abstraction
{
	/**
	 * Trims or zero-pads a key / IV
	 *
	 * @param   string $key  The key or IV to treat
	 * @param   int    $size The block size of the currently used algorithm
	 *
	 * @return  null|string  Null if $key is null, treated string of $size byte length otherwise
	 */
	public function resizeKey($key, $size)
	{
		if (empty($key))
		{
			return null;
		}

		$keyLength = strlen($key);

		if (function_exists('mb_strlen'))
		{
			$keyLength = mb_strlen($key, 'ASCII');
		}

		if ($keyLength == $size)
		{
			return $key;
		}

		if ($keyLength > $size)
		{
			if (function_exists('mb_substr'))
			{
				return mb_substr($key, 0, $size, 'ASCII');
			}

			return substr($key, 0, $size);
		}

		return $key . str_repeat("\0", ($size - $keyLength));
	}

	/**
	 * Returns null bytes to append to the string so that it's zero padded to the specified block size
	 *
	 * @param   string $string    The binary string which will be zero padded
	 * @param   int    $blockSize The block size
	 *
	 * @return  string  The zero bytes to append to the string to zero pad it to $blockSize
	 */
	protected function getZeroPadding($string, $blockSize)
	{
		$stringSize = strlen($string);

		if (function_exists('mb_strlen'))
		{
			$stringSize = mb_strlen($string, 'ASCII');
		}

		if ($stringSize == $blockSize)
		{
			return '';
		}

		if ($stringSize < $blockSize)
		{
			return str_repeat("\0", $blockSize - $stringSize);
		}

		$paddingBytes = $stringSize % $blockSize;

		return str_repeat("\0", $blockSize - $paddingBytes);
	}
}


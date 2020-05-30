<?php

namespace PhpOffice\PhpSpreadsheet\Reader;


/**  Define a Read Filter class implementing IReadFilter  */
class chunkReadFilter implements IReadFilter
{
	private $startRow = 0;

	private $endRow = 0;

	/**
	 * We expect a list of the rows that we want to read to be passed into the constructor.
	 *
	 * @param mixed $startRow
	 * @param mixed $chunkSize
	 */
	public function __construct($startRow, $chunkSize)
	{
		$this->startRow = $startRow;
		$this->endRow = $startRow + $chunkSize;
	}

	public function readCell($column, $row, $worksheetName = '')
	{
		//  Only read the heading row, and the rows that were configured in the constructor
		if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow))
		{
			return true;
		}

		return false;
	}
}
<?php

namespace Drupal\drush_extra\Helpers;

use Drush\Drush;

class TableHelper
{
	/**
	 * @var array
	 */
	protected $headerRows;

	/**
	 * @var array
	 */
	protected $rows;

	/**
	 * @var int
	 */
	protected $columnsCount;

	public function __construct()
	{
		$this->columnsCount = 0;
		$this->headerRows = [];
		$this->rows = [];
	}

	public function getHeaderRows(): array
	{
		return $this->headerRows;
	}

	public function getRows(): array
	{
		return $this->rows;
	}

	public function addHeaderRow(array $row)
	{
		$this->headerRows[] = $row;

		$this->calculateColumnsCount();
	}

	public function addHeaderRowColumn(string $column)
	{
		$this->headerRows[0][] = $column;
	}

	public function addRow(array $row, string|int $key = null)
	{
		if ($key) {
			$this->rows[$key] = $row;
		} else {
			$this->rows[] = $row;
		}

		$this->calculateColumnsCount();
	}

	public function addRowColumn(string $column, string|int $key)
	{
		$this->rows[$key][] = $column;
	}

	public function addEmptyRow()
	{
		$row = array_fill(0, $this->getRowSize(), '');

		$this->addRow($row);
	}

	public function addRowWithOnlyLastColumn(string|int $columnContent)
	{
		$row = array_fill(0, $this->getRowSize(), '');
		$row[$this->getRowSize()] = $columnContent;

		$this->addRow($row);
	}

	private function calculateColumnsCount()
	{
		if ($this->columnsCount !== 0) {
			return;
		}

		if (!empty($this->headerRows)) {
			$this->columnsCount = count($this->headerRows[0]);
			return;
		}

		$this->columnsCount = max(array_map('count', $this->rows));
	}

	private function getRowSize()
	{
		return $this->columnsCount > 0 ? $this->columnsCount - 1 : 0;
	}
}

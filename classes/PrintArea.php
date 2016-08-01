<?php

class PrintArea extends Upf
{
	private $_x;
	private $_y;
	private $_width;
	private $_height;

	private $_elements = [];

	public function __construct($xMm, $yMm, $widthMm, $heightMm)
	{
		if ($heightMm > 55) {
			trigger_error("Max height for PrintArea is 55mm.");
		}

		$this->_x = $this->toPoint($xMm);
		$this->_y = $this->toPoint($yMm);
		$this->_width = $this->toPoint($widthMm);
		$this->_height = $this->toPoint($heightMm);
	}

	public function addElement($element)
	{
		$element->setPrintArea($this);
		$this->_elements[] = $element;
	}

	public function getElements()
	{
		return $this->_elements;
	}

	public function getBBox()
	{
		return [
			'x' => $this->_x,
			'x2' => $this->_x + $this->_width,
			'y' => $this->_y,
			'y2' => $this->_y + $this->_height,
			'width' => $this->_width,
			'height' => $this->_height,
		];
	}

	public function addText($text, $xMm, $yMm, $widthMm, $heightMm)
	{
		$textDesignElement = new TextDesignElement($text, $xMm, $yMm, $widthMm, $heightMm, $this);
		$this->_elements[] = $textDesignElement;

		return $textDesignElement;
	}

	public function toString()
	{
		$elementsCount = count($this->_elements);

		$area = "\t\t\t\tObject:PrintArea" . PHP_EOL;
		$area .= "\t\t\t\t{" . PHP_EOL;
		$area .= "\t\t\t\t\t" . round($this->_height) . ',' . round($this->_width) . ',';
			$area .= round($this->_x) . ',' . round($this->_y) . PHP_EOL;
		$area .= "\t\t\t\t\t" . $elementsCount . PHP_EOL;

		foreach ($this->_elements as $element) {
			$area .= $element->toString();
		} 

		$area .= "\t\t\t\t}" . PHP_EOL;

		return $area;
	}
}
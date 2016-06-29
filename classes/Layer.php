<?php

class Layer extends Upf
{
	private $_name;

	// array = [red, green, blue, alpha];  item(0..255)
	private $_rgba;

	private $_printAreas = [];

	public function __construct($name, $rgba)
	{
		$this->_name = $name;

		if (!is_array($rgba) || count($rgba) !== 4) {
			trigger_error("$rgba needs to be specific array format: [red, green, blue, alpha]");
		} else {
			$this->_rgba = $rgba;
		}
	}

	public function addPrintArea($xMm, $yMm, $widthMm, $heightMm)
	{
		$this->_printAreas[] = new PrintArea($xMm, $yMm, $widthMm, $heightMm);
	}

	public function getPrintArea($index)
	{
		//print_r($this->_printAreas[$index]);
		return $this->_printAreas[$index];
	}

	public function toString()
	{
		$printAreasCount = count($this->_printAreas);

		$layer = "\t\t\tObject:Layer" . PHP_EOL;
		$layer .= "\t\t\t{" . PHP_EOL;

		$layer .= "\t\t\t\t" . $this->_name . ',' . str_replace(' ', '', $this->_name) . ',';
		$layer .= $this->_rgba[3] . ',' . $this->_rgba[0] . ',' . $this->_rgba[1] . ',' . $this->_rgba[2] . PHP_EOL;
		$layer .= "\t\t\t\t" . $printAreasCount . PHP_EOL;

		foreach ($this->_printAreas as $printArea) {
			$layer .= $printArea->toString();
		}

		$layer .= "\t\t\t}" . PHP_EOL;

		return $layer;
	}
}
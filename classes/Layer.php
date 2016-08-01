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
		return $this->_printAreas[$index];
	}

	public function optimitePrintAreas()
	{
		$allTextElements = [];

		foreach ($this->_printAreas as $printArea) {
			$elements = $printArea->getElements();

			foreach ($elements as $element) {
				if (get_class($element) === 'TextDesignElement') {
					$allTextElements[] = $element;
				}
			}
		}

		/*
		 * get most of the texts into one or more PrintAreas
		 */
		$printAreas = [];
		$printArea = [];

		for ($i=0; $i<count($allTextElements); $i++) {
			$printArea = [$allTextElements[$i]];
			$firstElIndex = $i;

			for ($j=$i+1; $j<count($allTextElements); $j++) {
				$firstBBox = $allTextElements[$firstElIndex]->getBBox();
				$secondBBox = $allTextElements[$j]->getBBox();

				if ($this->toMm($secondBBox['y2'] - $firstBBox['y']) <= 55) {
					$printArea[] = $allTextElements[$j];
					$i = $j;
				} else {
					break;
				}
			}

			$printAreas[] = $printArea;	
		}

		/*
		 * remove old printareas and create new ones
		 */
		$newPrintAreas = [];

		foreach ($printAreas as $printArea) {
			$firstElementBBox = $printArea[0]->getBBox();
			$lastElementBBox = $printArea[count($printArea)-1]->getBBox();

			$height = $lastElementBBox['y2'] - $firstElementBBox['y'];

			$newPrintArea = new PrintArea(
				$this->toMm($firstElementBBox['x']), 
				$this->toMm($firstElementBBox['y']), 
				$this->toMm($firstElementBBox['width']), 
				$this->toMm($height)
			);

			$firstElementPrintArea = $printArea[0]->getPrintArea();
			$firstElementPrintAreaBBox = $firstElementPrintArea->getBBox();

			foreach ($printArea as $element) {
				$elBBox = $element->getBBox();
				$elPrintAreaBBox = $element->getPrintArea()->getBBox();
				$y = $elPrintAreaBBox['y'] - $firstElementPrintAreaBBox['y'];
				$y += $elBBox['real']['y'] - $elBBox['y'];
				
				$element->attr('y', $y);
				$newPrintArea->addElement($element);
			}

			$newPrintAreas[] = $newPrintArea;
		}

		$this->_printAreas = $newPrintAreas;
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
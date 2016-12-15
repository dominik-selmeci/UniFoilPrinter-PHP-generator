<?php

class Layer extends Upf
{
	private $_name;
	private $_availablePrintAreaSide;

	// array = [red, green, blue, alpha];  item(0..255)
	private $_rgba;

	private $_printAreas = [];

	public function __construct($name, $rgba, $availablePrintAreaSide)
	{
		$this->_name = $name;
		$this->_availablePrintAreaSide = $availablePrintAreaSide;

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

	public function optimizePrintAreas()
	{
		$allTextElements = [];

		/*
		 * get all text elements
		 */
		foreach ($this->_printAreas as $printArea) {
			$elements = $printArea->getElements();

			foreach ($elements as $element) {
				if (get_class($element) === 'TextDesignElement') {
					$allTextElements[] = $element;
				}
			}
		}

		/*
		 * sort text elements by "y" position
		 */
		$sortByY = function($a, $b) {
			$aBBox = $a->getBBox();
			$bBBox = $b->getBBox();

			if ($aBBox['y'] === $bBBox['y']) {
				return 0;
			}

			if ($aBBox['y'] > $bBBox['y']) {
				return 1;
			} else {
				return -1;
			}
		};

		usort($allTextElements, $sortByY);

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

			// height must be exactly as in default template cca 56 mm
			// if < 56mm, then printer prints on bad side of the document
			$height = 672;
			$availablePASBBox = $this->_availablePrintAreaSide->getBBox();
			$paWidth = $availablePASBBox['width'] - 2* $availablePASBBox['margin'];

			$newPrintArea = new PrintArea(
				$this->toMm(0), 
				$this->toMm($firstElementBBox['y']), 
				$this->toMm($paWidth), 
				$this->toMm($height)
			);

			$firstElementPrintArea = $printArea[0]->getPrintArea();
			$firstElementPrintAreaBBox = $firstElementPrintArea->getBBox();

			foreach ($printArea as $element) {
				$elBBox = $element->getBBox();
				$elPrintAreaBBox = $element->getPrintArea()->getBBox();
				
				$y = $elPrintAreaBBox['y'] - $firstElementPrintAreaBBox['y'];
				$y += $elBBox['real']['y'] - $elBBox['y'];

				$element->attr([
					'y' => $y
				]);

				$newPrintArea->addElement($element);
			}

			$newPrintAreas[] = $newPrintArea;
		}

		$this->_printAreas = $newPrintAreas;
	}

	private function _getMinAttr($elements, $attr)
	{
		$min = null;

		foreach ($elements as $element) {
			$bbox = $element->getBBox();

			if ($bbox[$attr] < $min || $min === null) {
				$min = $bbox[$attr];
			}
		}

		return $min;
	}

	private function _getMaxAttr($elements, $attr)
	{
		$max = null;

		foreach ($elements as $element) {
			$bbox = $element->getBBox();

			if ($bbox[$attr] > $max || $max === null) {
				$max = $bbox[$attr];
			}
		}

		return $max;
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
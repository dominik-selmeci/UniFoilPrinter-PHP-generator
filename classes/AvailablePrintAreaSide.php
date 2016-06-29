<?php

class AvailablePrintAreaSide extends Upf
{
	private $_type;
	const ALLOWED_TYPES = ['front', 'spine', 'back'];

	private $_width;
	private $_height;
	private $_x;
	private $_y;

	private $_layers = [];
	private $_currentLayer;

	public function __construct($type)
	{
		if (in_array($type, self::ALLOWED_TYPES)) {
			$this->_type = $type;

			$this->_addLayer('Metallic Gold', [255,215,0,255]);
			$this->_addLayer('Metallic Silver', [192,192,192,255]);
			$this->_addLayer('Metallic Blue', [97,147,242,255]);
			$this->_addLayer('Metallic Red', [254,125,120,255]);
			$this->_addLayer('Black', [0,0,0,255]);
			$this->_addLayer('Gold OffsetSheet', [255,215,0,255]);
			$this->_addLayer('Silver OffsetSheet', [192,192,192,255]);

			$this->setLayer('Metallic Gold');
		} else {
			trigger_error("'{$type}' is not allowed type for AvailablePrintArea.");
		}
	}

	public function setLayer($name)
	{
		$this->_currentLayer = $this->_layers[$name];
	}

	public function getPrintArea($index)
	{
		return $this->_currentLayer->getPrintArea($index);
	}

	public function addPrintArea($xMm, $yMm, $widthMm, $heightMm)
	{
		$this->_currentLayer->addPrintArea($xMm, $yMm, $widthMm, $heightMm);
	}

	public function setSize($height, $width)
	{
		$this->_height = $height;
		$this->_width = $width;
	}

	public function setPosition($x, $y)
	{
		$this->_x = $x;
		$this->_y = $y;
	}

	public function toString($margin)
	{
		$countLayers = count($this->_layers);
		$width = ($this->_type === 'spine') ? ($this->_width - 2*$this->toPoint(3)) : ($this->_width - 2*$margin);
		$x = ($this->_type === 'spine') ? ($this->_x - $this->toPoint(3)) : $this->_x;

		$front = "\tObject:AvailablePrintAreaSide" . PHP_EOL;
		$front .= "\t{" . PHP_EOL;

		// height,width, Front, x,y
		$front .= "\t\t" . round($this->_height - 2*$margin) . ',' . round($width) . ',' . ucfirst($this->_type) . ',';
		$front .= round($x) . ',' . round($this->_y) . PHP_EOL;
		$front .= "\t\t" . $countLayers . PHP_EOL; 

		foreach ($this->_layers as $layer) {
			$front .= $layer->toString();
		}

		$front .= "\t}" . PHP_EOL;

		return $front;
	}

	private function _addLayer($name, $rgba)
	{
		$this->_layers[$name] = new Layer($name, $rgba);
	}
}
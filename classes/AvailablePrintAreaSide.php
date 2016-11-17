<?php

class AvailablePrintAreaSide extends Upf
{
	private $_type;
	private $_allowedTypes = ['front', 'spine', 'back'];

	private $_isHorizontalSpine = false;

	private $_width;
	private $_height;
	private $_x;
	private $_y;
	private $_margin;

	private $_layers = [];
	private $_currentLayer;

	public function __construct($type)
	{
		if (in_array($type, $this->_allowedTypes)) {
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

	public function getBBox()
	{
		return [
			'x' => $this->_x,
			'y' => $this->_y,
			'width' => $this->_width,
			'height' => $this->_height,
			'margin' => $this->_margin,
		];
	}

	public function setLayer($name)
	{
		$this->_currentLayer = $this->_layers[$name];
	}

	public function getLayer($name = null)
	{
		if ($name === null) {
			return $this->_currentLayer;
		} else {
			return $this->_layers[$name];
		}
	}

	public function setHorizontalSpine($bool = true)
	{
		$this->_isHorizontalSpine = $bool ? true : false;
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

	public function setMargin($margin)
	{
		$this->_margin = $margin;
	}

	public function toString($margin)
	{
		$countLayers = count($this->_layers);

		$width = round($this->_width - 2*$margin);
		$height = round($this->_height - 2*$margin);
		$type = ucfirst($this->_type);
		$x = round($this->_x);
		$y = round($this->_y);

		if ($this->_type === 'spine') {
			if ($this->_isHorizontalSpine) {
				$height = round($this->_height - 2*$this->toPoint(3));
				$x = round($this->_x);
				$y = round($this->_y + $this->toPoint(3));
			} else {
				$width = round($this->_width - 2*$this->toPoint(3));
				$x = round($this->_x - $this->toPoint(3));
			}
		}

		$front = "\tObject:AvailablePrintAreaSide" . PHP_EOL;
		$front .= "\t{" . PHP_EOL;

			$front .= "\t\t{$height},{$width},{$type},{$x},{$y}" . PHP_EOL;
			
			// there are no layers in spine (template OR_VERTICALSPINE)
			if ($this->_type === 'spine' && !$this->_isHorizontalSpine) {
				$front .= "\t\t0" . PHP_EOL;
			} else {
				$front .= "\t\t{$countLayers}" . PHP_EOL; 

				foreach ($this->_layers as $layer) {

					$front .= $layer->toString();
				}
			}

		$front .= "\t}" . PHP_EOL;

		return $front;
	}

	private function _addLayer($name, $rgba)
	{
		$this->_layers[$name] = new Layer($name, $rgba, $this);
	}
}
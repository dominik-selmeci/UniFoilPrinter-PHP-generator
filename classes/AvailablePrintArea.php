<?php

class AvailablePrintArea extends Upf
{
	private $_type;
	const ALLOWED_TYPES = ['front', 'spine', 'back'];

	private $_width;
	private $_height;
	private $_x;
	private $_y;

	public function __construct($type)
	{
		if (in_array($type, ALLOWED_TYPES)) {
			$this->_type = $type;
		} else {
			trigger_error("'{$type}' is not allowed type for AvailablePrintArea.");
		}
	}

	protected function setSize($heightMm, $widthMm)
	{
		$this->_height = $this->toPoint($heightMm);
		$this->_width = $this->toPoint($widthMm);
	}

	protected function setPosition($xMm, $yMm)
	{
		$this->_x = $this->toPoint($xMm);
		$this->_y = $this->toPoint($yMm);
	}

	protected function toString()
	{
		$front = "Object:AvailablePrintArea" . PHP_EOL;
		$front .= "{" . PHP_EOL;
		$front .= round($this->_height) . ',' . round($this->_width) . ",10,10,Aluminium,Metallic Gold" . PHP_EOL;
			$front .= "\tObject:AvailablePrintAreaSide" . PHP_EOL;
			$front .= "\t{" . PHP_EOL;

			// height,width, Front, x,y
			$front .= "\t\t" . round($this->_height) . ',' . round($this->_width) . ',Front,' . round($this->_x) . ',' . round($this->_y) . PHP_EOL;

			$front .= "\t\t" . '0' . PHP_EOL;
			$front .= "\t}" . PHP_EOL;
		$front .= "0" . PHP_EOL;
		$front .= "}" . PHP_EOL;

		return $front;
	}
}
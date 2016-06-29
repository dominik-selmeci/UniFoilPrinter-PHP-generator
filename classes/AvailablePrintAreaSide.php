<?php

class AvailablePrintAreaSide extends Upf
{
	private $_type;
	const ALLOWED_TYPES = ['front', 'spine', 'back'];

	private $_width;
	private $_height;
	private $_x;
	private $_y;

	public function __construct($type)
	{
		if (in_array($type, self::ALLOWED_TYPES)) {
			$this->_type = $type;
		} else {
			trigger_error("'{$type}' is not allowed type for AvailablePrintArea.");
		}
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
		$width = ($this->_type === 'spine') ? ($this->_width - 2*$this->toPoint(3)) : ($this->_width - 2*$margin);
		$x = ($this->_type === 'spine') ? ($this->_x - $this->toPoint(3)) : $this->_x;

		$front = "\tObject:AvailablePrintAreaSide" . PHP_EOL;
		$front .= "\t{" . PHP_EOL;

		// height,width, Front, x,y
		$front .= "\t\t" . round($this->_height - 2*$margin) . ',' . round($width) . ',' . ucfirst($this->_type) . ',';
		$front .= round($x) . ',' . round($this->_y) . PHP_EOL;

		//number of layers
		$front .= "\t\t" . '0' . PHP_EOL; 
		$front .= "\t}" . PHP_EOL;

		return $front;
	}
}
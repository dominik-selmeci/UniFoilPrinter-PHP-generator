<?php

class AvailablePrintArea extends Upf
{
	private $_type;
	const ALLOWED_TYPES = ['front', 'spine', 'back'];

	private $_width;
	private $_height;
	private $_x;
	private $_y;

	private $_material = 'Aluminium';
	const ALLOWED_MATERIALS = ['Aluminium', 'Graphite', 'Quartz', 'Azur', 'Ruby', 'Gold', 'Bordeaux', 'DarkGreen', 'DarkBlue', 'Black', 'PUCoatedMaterial', 'Leather', 'Matt', 'Clear'];

	public function __construct($type)
	{
		if (in_array($type, self::ALLOWED_TYPES)) {
			$this->_type = $type;
		} else {
			trigger_error("'{$type}' is not allowed type for AvailablePrintArea.");
		}
	}

	public function setSize($heightMm, $widthMm)
	{
		$this->_height = $this->toPoint($heightMm);
		$this->_width = $this->toPoint($widthMm);
	}

	public function setPosition($xMm, $yMm)
	{
		$this->_x = $this->toPoint($xMm);
		$this->_y = $this->toPoint($yMm);
	}

	public function setMaterial($material)
	{
		if (in_array($material, self::ALLOWED_MATERIALS)) {
			$this->_material = $material;
		} else {
			trigger_error("'" . $material . '\' material doesn\'t exist.');
		}
	}

	public function getParameters()
	{
		return [
			'x' => $this->_x,
			'y' => $this->_y,
			'width' => $this->_width,
			'height' => $this->_height,
		];
	}

	public function toString()
	{
		$front = "Object:AvailablePrintArea" . PHP_EOL;
		$front .= "{" . PHP_EOL;
		$front .= round($this->_height) . ',' . round($this->_width) . ",10,10," . $this->_material . ",Metallic Gold" . PHP_EOL;
			$front .= "\tObject:AvailablePrintAreaSide" . PHP_EOL;
			$front .= "\t{" . PHP_EOL;

			// height,width, Front, x,y
			$front .= "\t\t" . round($this->_height) . ',' . round($this->_width) . ',' . ucfirst($this->_type) . ',';
			$front .= round($this->_x) . ',' . round($this->_y) . PHP_EOL;

			$front .= "\t\t" . '0' . PHP_EOL;
			$front .= "\t}" . PHP_EOL;
		$front .= "0" . PHP_EOL;
		$front .= "}" . PHP_EOL;

		return $front;
	}
}
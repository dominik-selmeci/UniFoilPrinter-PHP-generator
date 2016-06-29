<?php

class Layer extends Upf
{
	private $_name;

	// array = [red, green, blue, alpha];  item(0..255)
	private $_rgba;

	public function __construct($name, $rgba)
	{
		$this->_name = $name;

		if (!is_array($rgba) || count($rgba) !== 4) {
			trigger_error("$rgba needs to be specific array format: [red, green, blue, alpha]");
		} else {
			$this->_rgba = $rgba;
		}
	}

	public function toString()
	{
		$printAreasCount = 0;

		$layer = "\t\t\tObject:Layer" . PHP_EOL;
		$layer .= "\t\t\t{" . PHP_EOL;

		$layer .= "\t\t\t\t" . $this->_name . ',' . str_replace(' ', '', $this->_name) . ',';
		$layer .= $this->_rgba[0] . ',' . $this->_rgba[1] . ',' . $this->_rgba[2] . ',' . $this->_rgba[3] . PHP_EOL;
		$layer .= "\t\t\t\t" . $printAreasCount . PHP_EOL;

		//TODO get printareas->toString()

		$layer .= "\t\t\t}" . PHP_EOL;

		return $layer;
	}
}
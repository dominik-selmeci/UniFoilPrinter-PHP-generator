<?php

/**
 * UniFoilPrinter PHP generator
 *
 * Author: Bc. Dominik Šelmeci
 */
class UpfPhpGenerator
{
	const MM_CONST = 11.8110236220472;
	private $_templateName;

	const ALLOWED_TYPES = ['1a', '1b', '2a'];
	private $_type = '1a';

	const ALLOWED_MATERIALS = ['cardboard', 'leather', 'plastic'];
	private $_material = 'cardboard';

	const ALLOWED_SOFTNESS = ['soft', 'hard'];
	private $_softness = 'soft';

	//cover parameters
	private $_height;
	private $_backWidth;
	private $_middleWidth;
	private $_frontWidth;

	//window parameters for 1b template
	private $_window = [
		'x2' => null,
		'y' => null,
		'width' => null,
		'height' => null,
	];

	public function __construct($templateName)
	{
		$this->_templateName = $templateName;
	}

	public function setType($type)
	{
		if (in_array($type, self::ALLOWED_TYPES)) {
			$this->_type = $type;
		} else {
			trigger_error("'" . $type . '\' type doesn\'t exist.');
		}
	}

	public function setMaterial($material)
	{
		if (in_array($material, self::ALLOWED_MATERIALS)) {
			$this->_material = $material;
		} else {
			trigger_error("'" . $material . '\' material doesn\'t exist.');
		}
	}

	public function setSoftness($softness)
	{
		if (in_array($softness, self::ALLOWED_SOFTNESS)) {
			$this->_softness = $softness;
		} else {
			trigger_error("'" . $softness . '\' softness doesn\'t exist.');
		}
	}

	public function setSize($heightMm, $backWidthMm, $middleWidthMm = null, $frontWidthMm = null)
	{
		$this->_height = $this->toPoint($heightMm);

		//template 2a || 1a,1b
		if (empty($middleWidthMm) && empty($frontWidthMm)) { 
			$this->_frontWidth = $this->toPoint($backWidthMm);
		} else {
			$this->_backWidth = $this->toPoint($backWidthMm);
			$this->_middleWidth = $this->toPoint($middleWidthMm);
			$this->_frontWidth = $this->toPoint($frontWidthMm);
		}	
	}

	public function setWindow($x2Mm, $yMm, $widthMm, $heightMm)
	{
		$this->_window['x2'] = $this->toPoint($x2Mm);
		$this->_window['y'] = $this->toPoint($yMm);
		$this->_window['width'] = $this->toPoint($widthMm);
		$this->_window['height'] = $this->toPoint($heightMm);
	}

	public function toString()
	{
		$a = $this->toPoint(19);
		$b = $this->toPoint(3);
		$width = $this->_frontWidth + $this->_middleWidth + $this->_backWidth;
		
		if ($this->_type === '1a' || $this->_type === '1b') {
			$sizes = [
				$this->_height,
				$this->_frontWidth,
				$this->_backWidth,
				0,
				$this->_middleWidth,
				0,
				$a, $a, $a, $a,
				$a, $b, $a, $b,
				$a, $a, $a, $a,
			];
		}

		if ($this->_type === '1b') {
			$sizes[] = 1;
			$sizes[] = $width - $this->_window['x2'] - $this->_window['width']; //x
			$sizes[] = $this->_window['y'];
			$sizes[] = $this->_window['height'];
			$sizes[] = $this->_window['width'];
		}

		if ($this->_type === '2a') {
			$sizes = [
				$this->_height,
				$this->_frontWidth,
				$b, $b, $b, $b
			];
		}

		$upf = "#UPFVERSION:1.1" . PHP_EOL;
		$upf .= "OR_VERTICALSPINE" . PHP_EOL;
		$upf .= "template_" . strtoupper($this->_type) . PHP_EOL;
		$upf .= "Object:template" . strtoupper($this->_type) . PHP_EOL;

		$upf .= "{" . PHP_EOL;
			$upf .= "\t" . $this->_templateName . ',type_' . strtoupper($this->_type) . ',';
			$upf .= implode(',', $sizes);
			$upf .= "," . strtoupper($this->_material) . "," . strtoupper($this->_softness) . PHP_EOL;
		$upf .= "}" . PHP_EOL;

		$upf .= "Object:AvailablePrintArea" . PHP_EOL;
		$upf .= "{" . PHP_EOL;
		$upf .= round($this->_height) . ',' . round($width) . ",10,10,Aluminium,Metallic Gold" . PHP_EOL;
			$upf .= "\tObject:AvailablePrintAreaSide" . PHP_EOL;
			$upf .= "\t{" . PHP_EOL;

			$upf .= "\t}" . PHP_EOL;
		$upf .= "0" . PHP_EOL;
		$upf .= "}" . PHP_EOL;

		return $upf;
	}

	public function saveTo($file)
	{
		$upf = $this->toString();
		file_put_contents($file, $upf);
	}

	public function toMm($point)
	{
		return $point / self::MM_CONST;
	}

	public function toPoint($mm, $round = null)
	{
		if (is_numeric($round)) {
			return round($mm * self::MM_CONST, $round);
		} else {
			return $mm * self::MM_CONST;
		}
	}
}
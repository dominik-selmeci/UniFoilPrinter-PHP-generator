<?php

require_once 'classes/Upf.php';
require_once 'classes/AvailablePrintArea.php';

/**
 * UniFoilPrinter PHP generator
 *
 * Author: Bc. Dominik Šelmeci
 */
class UpfPhpGenerator extends Upf
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
	private $_spineWidth;
	private $_frontWidth;

	// available print areas
	public $front;
	public $spine;
	public $back;

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

		$this->front = new AvailablePrintArea('front');
		$this->spine = new AvailablePrintArea('spine');
		$this->back = new AvailablePrintArea('back');
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

	public function setSize($heightMm, $backWidthMm, $spineWidthMm = null, $frontWidthMm = null)
	{
		$this->_height = $this->toPoint($heightMm);

		//template 2a || 1a,1b
		if (empty($spineWidthMm) && empty($frontWidthMm)) { 
			$this->_frontWidth = $this->toPoint($backWidthMm);
		} else {
			$this->_backWidth = $this->toPoint($backWidthMm);
			$this->_spineWidth = $this->toPoint($spineWidthMm);
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
		$b = $this->toPoint(8);
		$width = $this->_frontWidth + $this->_spineWidth + $this->_backWidth;
		
		if ($this->_type === '1a' || $this->_type === '1b') {
			$sizes = [
				$this->_height,
				$this->_frontWidth,
				$this->_backWidth,
				0,
				$this->_spineWidth,
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
			pa($this->front->getParameters());
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

		if ($this->_type === '1a' || $this->_type === '1b') {
			$upf .= $this->front->toString();
			$upf .= $this->spine->toString();
			$upf .= $this->back->toString();
		} 

		if ($this->_type === '2a') {
			$upf .= $this->front->toString();
		}	

		return $upf;
	}

	private function _toStringFront()
	{
		$front = "Object:AvailablePrintArea" . PHP_EOL;
		$front .= "{" . PHP_EOL;
		$front .= round($this->_height) . ',' . round($this->_frontWidth) . ",10,10,Aluminium,Metallic Gold" . PHP_EOL;
			$front .= "\tObject:AvailablePrintAreaSide" . PHP_EOL;
			$front .= "\t{" . PHP_EOL;
			// height,width, Front, x,y
			$front .= "\t\t" . $this->toPoint(150, 0) . ',' . $this->toPoint(80, 0) . ',Front,' . $this->toPoint(10, 0) . ',' . $this->toPoint(10, 0) . PHP_EOL;
			$front .= "\t\t" . '0' . PHP_EOL;


			$front .= "\t}" . PHP_EOL;
		$front .= "0" . PHP_EOL;
		$front .= "}" . PHP_EOL;

		return $front;
	}

	public function saveTo($file)
	{
		$upf = $this->toString();
		file_put_contents($file, $upf);
	}
}
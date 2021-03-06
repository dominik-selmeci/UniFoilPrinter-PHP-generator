<?php

require_once 'classes/Upf.php';
require_once 'classes/Layer.php';
require_once 'classes/AvailablePrintAreaSide.php';
require_once 'classes/PrintArea.php';
require_once 'classes/TextDesignElement.php';

/**
 * UniFoilPrinter PHP generator
 *
 * Author: Bc. Dominik Šelmeci
 */
class UpfPhpGenerator extends Upf
{
	const MM_CONST = 11.8110236220472;
	private $_templateName;

	private $_alowedTypes = ['1a', '1b', '2a'];
	private $_type = '1a';

	private $_isHorizontalSpine = false;

	private $_allowedMaterials = ['cardboard', 'leather', 'plastic'];
	private $_material = 'cardboard';

	private $_allowedPrintAreaMaterials = ['Aluminium', 'Graphite', 'Quartz', 'Azur', 'Ruby', 'Gold', 'Bordeaux', 'DarkGreen', 'DarkBlue', 'Black', 'PUCoatedMaterial', 'Leather', 'Matt', 'Clear'];
	private $_printAreaMaterial = 'Aluminium';

	private $_allowedSoftness = ['soft', 'hard'];
	private $_softness = 'soft';

	//cover parameters
	private $_height;
	private $_backWidth;
	private $_spineWidth;
	private $_frontWidth;
	private $_margin = 19;

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

		$this->front = new AvailablePrintAreaSide('front');
		$this->spine = new AvailablePrintAreaSide('spine');
		$this->back = new AvailablePrintAreaSide('back');

		$this->setMargin($this->_margin);
	}

	public function setType($type)
	{
		if (in_array($type, $this->_alowedTypes)) {
			$this->_type = $type;
		} else {
			trigger_error("'" . $type . '\' type doesn\'t exist.');
		}
	}

	public function setHorizontalSpine($bool = true)
	{
		$this->_isHorizontalSpine = $bool ? true : false;

		$this->front->setHorizontalSpine($bool);
		$this->spine->setHorizontalSpine($bool);
		$this->back->setHorizontalSpine($bool);
	}

	public function setMaterial($material)
	{
		if (in_array($material, $this->_allowedMaterials)) {
			$this->_material = $material;
		} else {
			trigger_error("'" . $material . '\' material doesn\'t exist.');
		}
	}

	public function setPrintAreaMaterial($material)
	{
		if (in_array($material, $this->_allowedPrintAreaMaterials)) {
			$this->_printAreaMaterial = $material;
		} else {
			trigger_error("'" . $material . '\' material doesn\'t exist.');
		}
	}

	public function setSoftness($softness)
	{
		if (in_array($softness, $this->_allowedSoftness)) {
			$this->_softness = $softness;
		} else {
			trigger_error("'" . $softness . '\' softness doesn\'t exist.');
		}
	}

	public function setMargin($marginMm)
	{
		$this->_margin = $this->toPoint($marginMm);

		if ($this->_isHorizontalSpine) {
			$frontX = $this->_margin;
			$frontY = $this->_margin;
			$spineX = $this->_margin;
			$spineY = $this->_frontWidth;
			$backX = $this->_margin;
			$backY = $this->_frontWidth + $this->_spineWidth + $this->_margin;
		} else {
			$frontX = $this->_margin + $this->_backWidth + $this->_spineWidth;
			$frontY = $this->_margin;
			$spineX = $this->_backWidth;
			$spineY = $this->_margin;
			$backX = $this->_margin;
			$backY = $this->_margin;
		}	

		$this->front->setPosition($frontX, $frontY);
		$this->spine->setPosition($spineX, $spineY);
		$this->back->setPosition($backX, $backY);

		$this->front->setMargin($this->_margin);
		$this->spine->setMargin($this->_margin);
		$this->back->setMargin($this->_margin);
	}

	public function setSize($heightMm, $backWidthMm, $spineWidthMm = null, $frontWidthMm = null)
	{
		$this->_height = $this->toPoint($heightMm);

		//template 2a || 1a,1b
		if (empty($spineWidthMm) && empty($frontWidthMm)) { 
			$this->_frontWidth = $this->toPoint($backWidthMm);

			$this->front->setSize($this->_height, $this->_frontWidth);
		} else {
			$this->_backWidth = $this->toPoint($backWidthMm);
			$this->_spineWidth = $this->toPoint($spineWidthMm);
			$this->_frontWidth = $this->toPoint($frontWidthMm);

			if ($this->_isHorizontalSpine) {
				$this->back->setSize($this->_backWidth, $this->_height);
				$this->spine->setSize($this->_spineWidth, $this->_height);
				$this->front->setSize($this->_frontWidth, $this->_height);
			} else {
				$this->back->setSize($this->_height, $this->_backWidth);
				$this->spine->setSize($this->_height, $this->_spineWidth);
				$this->front->setSize($this->_height, $this->_frontWidth);
			}
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
		$a = $this->_margin;
		$b = $this->toPoint(3);
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
			$sizes = [
				$this->_height,
				$this->_frontWidth,
				$b, $b, $b, $b
			];
		}

		$upf = "#UPFVERSION:1.1" . PHP_EOL;
		$upf .= ($this->_isHorizontalSpine ? "OR_HORIZONTALSPINE" : "OR_VERTICALSPINE") . PHP_EOL;
		$upf .= "template_" . strtoupper($this->_type) . PHP_EOL;
		
		$upf .= "Object:template" . strtoupper($this->_type) . PHP_EOL;
		$upf .= "{" . PHP_EOL;
			$upf .= "\t" . $this->_templateName . ',type_' . strtoupper($this->_type) . ',';
			$upf .= implode(',', $sizes);
			$upf .= "," . strtoupper($this->_material) . "," . strtoupper($this->_softness) . PHP_EOL;
		$upf .= "}" . PHP_EOL;

		$upf .= "Object:AvailablePrintArea" . PHP_EOL;
		$upf .= "{" . PHP_EOL;
		$upf .= round($this->_height) . ',' . round($width) . ",10,10," . $this->_printAreaMaterial . ",Metallic Gold" . PHP_EOL;

		if ($this->_type === '1a' || $this->_type === '1b') {
			$upf .= $this->front->toString($this->_margin);
			$upf .= $this->spine->toString($this->_margin);
			$upf .= $this->back->toString($this->_margin);
		} 

		if ($this->_type === '2a') {
			$upf .= $this->front->toString($this->_margin);
		}	

		// zero guidelines
		$upf .= "0" . PHP_EOL;
		$upf .= "}" . PHP_EOL;

		return $upf;
	}

	public function saveTo($file)
	{
		$upf = $this->toString();
		file_put_contents($file, $upf);
	}
}
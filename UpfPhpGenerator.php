<?php

/**
 * UniFoilPrinter PHP generator
 *
 * Author: Bc. Dominik Šelmeci
 */
class UpfPhpGenerator
{
	const MMCONST = 11.8110236220472;
	private $_templateName;
	private $_heightMm;
	private $_backWidthMm;
	private $_middleWidthMm;
	private $_frontWidthMm;

	public function __construct($templateName)
	{
		$this->_templateName = $templateName;
	}

	public function setSize($heightMm, $backWidthMm, $middleWidthMm, $frontWidthMm)
	{
		$this->_heightMm = $heightMm;
		$this->_backWidthMm = $backWidthMm;
		$this->_middleWidthMm = $middleWidthMm;
		$this->_frontWidthMm = $frontWidthMm;
	}

	public function toString()
	{
		$a = 19;
		$b = 3;
		$sizes = [
			$this->toPoint($this->_heightMm),
			$this->toPoint($this->_frontWidthMm),
			$this->toPoint($this->_backWidthMm),
			0,
			$this->toPoint($this->_middleWidthMm),
			0,
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($b),
			$this->toPoint($a),
			$this->toPoint($b),
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($a),
			$this->toPoint($a),
		];
		$widthMm = $this->_frontWidthMm + $this->_middleWidthMm + $this->_backWidthMm;

		$upf = "#UPFVERSION:1.1\n";
		$upf .= "OR_VERTICALSPINE\n";
		$upf .= "template_1A\n";
		$upf .= "Object:template1A\n";

		$upf .= "{\n";
			$upf .= "\t" . $this->_templateName . ',type_1A,';
			$upf .= implode(',', $sizes);
			$upf .= ",CARDBOARD,HARD\n";
		$upf .= "}\n";

		$upf .= "Object:AvailablePrintArea\n";
		$upf .= "{\n";
		$upf .= $this->toPoint($this->_heightMm, 0) . ',' . $this->toPoint($widthMm, 0) . ",10,10,Aluminium,Metallic Gold\n";
			$upf .= "\tObject:AvailablePrintAreaSide\n";
			$upf .= "\t{\n";

			$upf .= "\t}\n";
		$upf .= "0\n";
		$upf .= "}\n";

		return $upf;
	}

	public function toMm($point)
	{
		return $point / self::MMCONST;
	}

	public function toPoint($mm, $round = null)
	{
		if (is_numeric($round)) {
			return round($mm * self::MMCONST, $round);
		} else {
			return $mm * self::MMCONST;
		}
	}
}
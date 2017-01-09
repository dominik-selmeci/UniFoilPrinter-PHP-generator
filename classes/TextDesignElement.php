<?php

class TextDesignElement extends Upf
{
	// TODO - there are different font height for different font family
	const PT_TO_MM = 0.352778;

	private $_printArea;

	private $_text;
	private $_x;
	private $_y;
	private $_width;
	private $_height;

	private $_bold = false;
	private $_italic = false;
	private $_underline = false;

	private $_font = 'Times New Roman';
	private $_fontSize = 16;
	private $_align = 'Near';

	private $_allowedVerticalAligns = ['top', 'bottom', 'center', 'base'];
	private $_verticalAlign = 'top';

	public function __construct($text, $xMm, $yMm, $widthMm, $heightMm, $printArea)
	{
		$this->_text = $text;
		$this->_x = $this->toPoint($xMm);
		$this->_y = $this->toPoint($yMm);
		$this->_width = $this->toPoint($widthMm);
		$this->_height = $this->toPoint($heightMm);
		$this->_printArea = $printArea;

		return $this;
	}

	public function getBBox()
	{
		$printAreaBBox = $this->_printArea->getBBox();

		return [
			'x' => $this->_x + $printAreaBBox['x'],
			'x2' => $this->_x + $this->_width + $printAreaBBox['x'],
			'y' => $this->_getY() + $printAreaBBox['y'],
			'y2' => $this->_getY() + $this->_height + $printAreaBBox['y'],
			'width' => $this->_width,
			'height' => $this->_height,
			'real' => [
				'y' => $this->_y + $printAreaBBox['y'],
				'y2' => $this->_y + $this->_height + $printAreaBBox['y'],
			]
		];
	}

	public function attr($key, $value = null)
	{
		if (is_array($key)) {
			foreach ($key as $i => $val) {
				$index = '_' . $i;

				if (isset($this->$index)) {
					$this->$index = $val;
				}
			}
		} else {
			$key = '_' . $key;

			if (isset($this->$key)) {
				$this->$key = $value;
			}
		}
	}

	public function setBold($isBold = true)
	{
		$this->_bold = $isBold ? true : false;

		return $this;
	}

	public function setItalic($isItalic = true)
	{
		$this->_italic = $isItalic ? true : false;

		return $this;
	}

	public function setUnderline($isUnderline = true)
	{
		$this->_underline = $isUnderline ? true : false;

		return $this;
	}

	/*
	 * Set vertical align relative to Y position
	 */
	public function setVerticalAlign($align)
	{
		if (in_array($align, $this->_allowedVerticalAligns)) {
			$this->_verticalAlign = $align;
		} else {
			trigger_error("'" . $align . '\' align doesn\'t exist.');
		}

		return $this;
	}

	public function setFont($fontName)
	{
		$this->_font = $fontName;

		return $this;
	}

	public function setFontSize($fontSizePt)
	{
		$this->_fontSize = $fontSizePt;

		return $this;
	}

	public function setAlign($align)
	{
		switch ($align) {
			case 'left': $this->_align = 'Near'; break;
			case 'center': $this->_align = 'Center'; break;
			case 'right': $this->_align = 'Far'; break;
		}

		return $this;
	}

	public function setPrintArea(PrintArea $printArea)
	{
		$this->_printArea = $printArea;
	}

	public function getPrintArea()
	{
		return $this->_printArea;
	}

	public function toString()
	{
		$texts = explode("\n", $this->_text);

		$x = round($this->_x);
		$y = round($this->_getY());
		$width = round($this->_width);
		$height = round($this->_height);

		$font = trim($this->_font, "\x22\x27"); // sometimes there is " or '
		$align = $this->_align;
		$fontSize = $this->_fontSize;
		$formatting = $this->_getFontFormatting();

		$text = "\t\t\t\t\tObject:TextDesignElement" . PHP_EOL;
		$text .= "\t\t\t\t\t{" . PHP_EOL;

			$text .= "\t\t\t\t\t\t{$x},{$y},{$width},{$height},";
			$text .= "False,{$font},{$align},{$fontSize},0,0,False,0,0,0" . PHP_EOL;
			$text .= "\t\t\t\t\t\t{$formatting}" . PHP_EOL;
			$text .= "\t\t\t\t\t\tFalse" . PHP_EOL;

			$text .= "\t\t\t\t\t\t" . 'List<string>:' . count($texts) . PHP_EOL;
			$text .= "\t\t\t\t\t\t{" . PHP_EOL;

				foreach ($texts as $txt) {
					$text .= "\t\t\t\t\t\t\t" . iconv_strlen($txt) . PHP_EOL;
					$text .= "\t\t\t\t\t\t\t{" . PHP_EOL;
						$text .= $txt . PHP_EOL;
					$text .= "\t\t\t\t\t\t\t}" . PHP_EOL;
				}

			$text .= "\t\t\t\t\t\t}" . PHP_EOL;

		$text .= "\t\t\t\t\t}" . PHP_EOL;

		return $text;
	}

	private function _getY()
	{
		switch ($this->_verticalAlign) {
			case 'top': 
				$y = $this->_y; 
			break;

			case 'bottom': 
				$y = $this->_y - $this->toPoint($this->_fontSize * self::PT_TO_MM); 
			break;

			// TODO: maybe different font families have different % base position
			case 'base': 
				$y = $this->_y - $this->toPoint($this->_fontSize * self::PT_TO_MM) * 0.85; 
			break;

			case 'center': 
				$y = $this->_y - $this->toPoint($this->_fontSize * self::PT_TO_MM) * 0.5; 
			break;
		}

		return $y;
	}

	public function _getFontFormatting()
	{
		$formattingArr = [];

		if ($this->_bold || $this->_italic || $this->_underline) {
			if ($this->_bold) {
				$formattingArr[] = 'Bold';
			}

			if ($this->_italic) {
				$formattingArr[] = 'Italic';
			}

			if ($this->_underline) {
				$formattingArr[] = 'Underline';
			}

			return implode(', ', $formattingArr);
		} else {
			return 'Regular';
		}
	}
}
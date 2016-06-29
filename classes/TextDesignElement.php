<?php

class TextDesignElement extends Upf
{
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

	public function __construct($text, $xMm, $yMm, $widthMm, $heightMm)
	{
		$this->_text = $text;
		$this->_x = $this->toPoint($xMm);
		$this->_y = $this->toPoint($yMm);
		$this->_width = $this->toPoint($widthMm);
		$this->_height = $this->toPoint($heightMm);

		return $this;
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

	public function toString()
	{
		$texts = explode("\n", $this->_text);

		$text = "\t\t\t\t\tObject:TextDesignElement" . PHP_EOL;
		$text .= "\t\t\t\t\t{" . PHP_EOL;
		$text .= "\t\t\t\t\t\t" . round($this->_x) . ',' . round($this->_y) . ',';
		$text .= round($this->_width) . ',' . round($this->_height) . ',';
		$text .= 'False,' . $this->_font . ',' . $this->_align . ',' . $this->_fontSize . ',0,0,False,0,0,0' . PHP_EOL;
		$text .= "\t\t\t\t\t\t" . $this->_getFontFormatting() . PHP_EOL;
		$text .= "\t\t\t\t\t\tFalse" . PHP_EOL;
		$text .= "\t\t\t\t\t\t" . 'List<string>:' . count($texts) . PHP_EOL;
		$text .= "\t\t\t\t\t\t" . '{' . PHP_EOL;

		foreach ($texts as $txt) {
			$text .= "\t\t\t\t\t\t\t" . iconv_strlen($txt) . PHP_EOL;
			$text .= "\t\t\t\t\t\t\t{" . PHP_EOL;
			$text .= $txt . PHP_EOL;
			$text .= "\t\t\t\t\t\t\t}" . PHP_EOL;
		}

		$text .= "\t\t\t\t\t\t" . '}' . PHP_EOL;
		$text .= "\t\t\t\t\t}" . PHP_EOL;

		return $text;
	}

	public function _getFontFormatting()
	{
		if ($this->_bold || $this->_italic || $this->_underline) {
			$format = $this->_bold ? 'Bold' : '';

			if ($this->_italic) {
				$format .= ((strlen($format) > 0) ? ', ' : '') . 'Italic';
			}

			if ($this->_underline) {
				$format .= ((strlen($format) > 0) ? ', ' : '') . 'Underline';
			}

			return $format;
		} else {
			return 'Regular';
		}
	}
}
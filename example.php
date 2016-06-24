<?php

require_once 'UpfPhpGenerator.php';

$upf = new UpfPhpGenerator();

echo '<pre>';
print_r($upf->toString());
echo '</pre>';

/*

$upf->addText($x, $y, $width, $height, $text)
	->setFont($font, $fontSize, $align, $bold, $italic, $underline);

OR ?

$upf->addText($x, $y, $width, $height, $text)
	->setFont($font)
	->setFontSize($fontSize)
	->setAlign($align)
	->setBold($isBold)
	->setItalic($isItalic)
	->setUnderline($isUnderline);

$upf->addImage($x, $y, $width, $height, $src);

$upf->addBack($x, $y, $width, $height, $text)
	->setFont($font, $fontSize, $align, $bold, $italic, $underline);

$upf->saveToFile($path);*/
<?php

require_once 'UpfPhpGenerator.php';

$upf = new UpfPhpGenerator('My template name');
$upf->setSize(100, 20, 10, 20); 

echo '<pre>';
print_r($upf->toString());
echo '</pre>';

exit;

/*
 * How API should look like...
 */
$upf = new UpfPhpGenerator('My template name');
$upf->setSize($heightMm, $backWidthMm, $middleWidthMm, $frontWidthMm);
$upf->setType($type); // 1a, 1b, 2a
$upf->setMaterial($material); // cardboard, leather, plastic
$upf->setSoftness($softness); // soft, hard

$upf->setPrintArea('front'); // front, spine, back
$upf->setLayer('Mettalic Gold'); // Mettalic Gold, Mettalic Silver, Mettalic Blue, Mettalic Red, Black, Gold OffsetSheet, Silver OffsetSheet
$upf->addPrintArea()->addText($x, $y, $width, $height, $text);

$upf->back->layer('MettalicGold')->addPrintArea()->addText();
$upf->back->layer('MettalicGold')->printArea[0]	->addText($x, $y, $width, $height, $text)
												->setFont($font)
												->setFontSize($fontSize)
												->setAlign($align)
												->setBold($isBold)
												->setItalic($isItalic)
												->setUnderline($isUnderline);

$upf->addText($x, $y, $width, $height, $text)
	->setFont($font, $fontSize, $align, $bold, $italic, $underline);

// OR ?

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

$upf->saveToFile($path);
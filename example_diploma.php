<?php
require_once 'UpfPhpGenerator.php';

$upf = new UpfPhpGenerator('Diploma 45cm');

$heightMm = 304;
$widthMm = 450;
$frontWidthMm = 210;
$spineWidthMm = 15;
$backWidthMm = $widthMm - ($frontWidthMm + $spineWidthMm);
$margin = 19; //optional, default is set to 19

// sets type, material, softness, size and margin
$upf->setType('1a');
$upf->setMaterial('cardboard');
$upf->setSoftness('hard');
$upf->setSize($heightMm, $backWidthMm, $spineWidthMm, $frontWidthMm); 
$upf->setMargin($margin); 

// set current layer where PrintArea should be created
$upf->front->setLayer('Metallic Gold');


// set y postion to 100mm from top of cover
$printAreaY = 100 - $margin;

// add PrintArea ($xMm, $yMm, $widthMm, $heightMm)
$upf->front->addPrintArea(0,$printAreaY, ($frontWidthMm-2*$margin),40);
$upf->back->addPrintArea(0,20, ($backWidthMm-2*$margin),55);

// addText
$upf->front->getPrintArea(0)
	->addText('Test text with utf chars ĽĺŽŠyj', 0,0, ($frontWidthMm-2*$margin),20)
	->setBold(true)
	->setItalic(true)
	->setUnderline(true)
	->setFont('Verdana')
	->setFontSize(24);


// add Text, where text base = 130mm
$fontSize = 30;
$ptToMm = 0.352778;
$fontSizeMm = $fontSize * $ptToMm;

// PrintArea y position = 110 from top of cover 
$upf->back->addPrintArea(0,110 - $margin, ($backWidthMm-2*$margin),55);

$textY = 20; // move top of the text to 130mm
$textY -= $fontSizeMm * 0.85; //move top of the text to -85% of the text height
$upf->back->getPrintArea(1)
	->addText('ĺText base y = 130mmĺ', 0, $textY, ($frontWidthMm-2*$margin), $fontSize*0.5)
	->setFontSize($fontSize)
	->setFont('Verdana');

// add Text, where text base = 150mm
$fontSize = 10;
$fontSizeMm = $fontSize * $ptToMm;
$upf->back->getPrintArea(1)
	->addText('ĺText base y = 150mmĺ', 0, 40 - $fontSizeMm * 0.85, ($frontWidthMm-2*$margin), $fontSize*0.5)
	->setFontSize($fontSize)
	->setFont('Verdana');


echo '<pre>' . $upf->toString() . '</pre>';

if (!is_dir(realpath('./') . DIRECTORY_SEPARATOR . 'samples')) {
	mkdir(realpath('./') . DIRECTORY_SEPARATOR . 'samples');
}
$upf->saveTo(realpath('./') . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'test.upf');
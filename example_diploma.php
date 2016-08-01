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
//$printAreaY = 100 - $margin;

// add PrintArea ($xMm, $yMm, $widthMm, $heightMm)
//$upf->back->addPrintArea(0,20, ($backWidthMm-2*$margin),55);

// addTexts
for ($i=0; $i<10; $i++) {
	$fontSize = 10 + $i;
	$upf->front->addPrintArea(0,20 * ($i+1) + 1, ($frontWidthMm-2*$margin), $fontSize*0.5);

	$upf->front->getPrintArea($i)
		->addText(
			'ĺyjText top y = ' . (20 * ($i+1) + 20) . 'mm yjĺ,' . $i, //text
			0, // xMm
			$fontSize*0.85*0.352778, // yMm
			($frontWidthMm-2*$margin), //widthMm
			$fontSize*0.5 //heightMm
		)
		->setBold(true)
		->setItalic(true)
		->setUnderline(true)
		->setFont('Verdana')
		->setFontSize($fontSize)
		->setVerticalAlign('center');
}


for ($i=0; $i<10; $i++) {
	$fontSize = 10 + $i;
	$upf->back->addPrintArea(0,20 * ($i+1) + 1, ($backWidthMm-2*$margin), $fontSize*0.5);

	$upf->back->getPrintArea($i)
		->addText(
			'ĺyjText top y = ' . (20 * ($i+1) + 20) . 'mm yjĺ,' . $i, //text
			0, // xMm
			$fontSize*0.85*0.352778, // yMm
			($backWidthMm-2*$margin), //widthMm
			$fontSize*0.5 //heightMm
		)
		->setBold(true)
		->setItalic(true)
		->setUnderline(true)
		->setFont('Verdana')
		->setFontSize($fontSize)
		->setAlign('right')
		->setVerticalAlign('center');
}



/*// add Text, where text base = 130mm
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
	->setFont('Verdana');*/


$upf->front->getLayer()->optimitePrintAreas();

echo '<pre>' . $upf->toString() . '</pre>';

if (!is_dir(realpath('./') . DIRECTORY_SEPARATOR . 'samples')) {
	mkdir(realpath('./') . DIRECTORY_SEPARATOR . 'samples');
}
$upf->saveTo(realpath('./') . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'test.upf');
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
$upf->setHorizontalSpine();
$upf->setMaterial('cardboard');
$upf->setSoftness('hard');
$upf->setSize($heightMm, $backWidthMm, $spineWidthMm, $frontWidthMm); 
$upf->setMargin($margin); 

// set current layer where PrintArea should be created
$upf->front->setLayer('Metallic Gold');

// add printarea to spine
$upf->spine->addPrintArea(0,0, ($heightMm-2*$margin), $spineWidthMm-2*3);

$upf->spine->getPrintArea(0)
	->addText('Test text with utf chars ĽĺŽŠyj', 0,0, ($heightMm-2*$margin),$spineWidthMm-2*3)
	->setBold(true)
	->setItalic(true)
	->setUnderline(true)
	->setFont('Verdana')
	->setFontSize(12);

echo '<pre>' . $upf->toString() . '</pre>';

if (!is_dir(realpath('./') . DIRECTORY_SEPARATOR . 'samples')) {
	mkdir(realpath('./') . DIRECTORY_SEPARATOR . 'samples');
}
$upf->saveTo(realpath('./') . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'test.upf');
<?php
require_once 'UpfPhpGenerator.php';

$upf = new UpfPhpGenerator('Diploma 45cm');

$heightMm = 304;
$widthMm = 450;
$frontWidthMm = 210;
$spineWidthMm = 15;
$backWidthMm = $widthMm - ($frontWidthMm + $spineWidthMm);
// $margin = 19; //optional, default is set to 19

// sets basics and size
$upf->setType('1a');
$upf->setMaterial('cardboard');
$upf->setSoftness('hard');
$upf->setSize($heightMm, $backWidthMm, $spineWidthMm, $frontWidthMm); 
$upf->setMargin(19); 

echo '<pre>' . $upf->toString() . '</pre>';

if (!is_dir(realpath('./') . DIRECTORY_SEPARATOR . 'samples')) {
	mkdir(realpath('./') . DIRECTORY_SEPARATOR . 'samples');
}
$upf->saveTo(realpath('./') . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'test.upf');
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
$upf->front->addPrintArea(0,81, ($frontWidthMm-2*$margin),40);
$upf->back->addPrintArea(0,81, ($backWidthMm-2*$margin),55);

//$upf->front->getPrintArea($printAreaIndex)[$elementIndex]->doSomething();

echo '<pre>' . $upf->toString() . '</pre>';

if (!is_dir(realpath('./') . DIRECTORY_SEPARATOR . 'samples')) {
	mkdir(realpath('./') . DIRECTORY_SEPARATOR . 'samples');
}
$upf->saveTo(realpath('./') . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'test.upf');
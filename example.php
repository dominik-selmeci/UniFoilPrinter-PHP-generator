<?php

require_once 'UpfPhpGenerator.php';

// just simple helper
function pa($arr) {
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

/*
 * 2a example
 */
$upf = new UpfPhpGenerator('My template name');

$upf->setType('2a');
$upf->setMaterial('cardboard');
$upf->setSoftness('hard');
$upf->setSize(200, 100); 

pa($upf->toString());
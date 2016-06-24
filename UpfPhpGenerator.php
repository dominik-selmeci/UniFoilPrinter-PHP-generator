<?php

/**
 * UniFoilPrinter PHP generator
 *
 * Author: Bc. Dominik Šelmeci
 */
class UpfPhpGenerator
{
	private $container = '';

	public function __construct()
	{
		$this->container .= "#UPFVERSION:1.1\n";
		$this->container .= "OR_VERTICALSPINE\n";
		$this->container .= "template_1A\n";

		$this->container .= "Object:template1A\n";
		$this->container .= "{\n";
		$this->container .= "\tTITULKA (45CM),type_1A,3543.30708661417,2362.20472440945,2716.53543307087,0,236.220472440945,0,224.409448818898,224.409448818898,224.409448818898,224.409448818898,224.409448818898,35.4330708661417,224.409448818898,35.4330708661417,224.409448818898,224.409448818898,224.409448818898,224.409448818898,CARDBOARD,HARD\n";
		$this->container .= "}\n";

	}

	public function toString()
	{
		return $this->container;
	}
}
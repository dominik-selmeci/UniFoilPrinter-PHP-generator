<?php

class Upf
{
	const MM_CONST = 11.8110236220472;

	public function toMm($point)
	{
		return $point / self::MM_CONST;
	}

	public function toPoint($mm, $round = null)
	{
		if (is_numeric($round)) {
			return round($mm * self::MM_CONST, $round);
		} else {
			return $mm * self::MM_CONST;
		}
	}
}
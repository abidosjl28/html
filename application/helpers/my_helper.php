<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('version'))
{
	function version()
	{
		$v = 1;
		return '?ver='.$v;
	}
}
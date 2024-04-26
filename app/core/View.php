<?php

namespace App\core;

class View
{
	public $content_view, $template_view, $data;
	
	function generate($content_view, $template_view, $data = null)
	{
		include VIEW . DIRECTORY_SEPARATOR .$template_view;
	}
}

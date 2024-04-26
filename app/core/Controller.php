<?php

namespace App\core;

class Controller
{
	public $model;
	public $view;
	
	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{
		// todo	
	}
}
<?php

namespace App\controllers;

use App\core\Controller;

class Controller_404 extends Controller
{	
	function action_index()
	{
		$this->view->generate('404_view.phtml', 'template_view.phtml');
	}

}
<?php

namespace App\controllers;

use App\core\Controller;

class Controller_Main extends Controller
{
    public function action_index()
    {
        $this->view->generate('main_view.phtml', 'template_view.phtml'); 
    }
}
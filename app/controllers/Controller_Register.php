<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Postclear;
use App\models\Model_Postfilter;
use App\models\Model_User;

class Controller_Register extends Controller
{
	public function action_index()
	{
		$sms = (object) array();
		$sms->login_status = "access_denied";
		
		if (isset($_POST['tkn']) && $_POST['tkn'] == $_SESSION['tkn'])
		{
			$filter = new Model_Postfilter(array('password' => $_POST['password'], 'login' => $_POST['login']));
			$filterpost = $filter->checkPost();
			
			if (!empty($_POST['login']) and !empty($_POST['password']) and !empty($_POST['runame']) and empty($filterpost))
			{
				$login = $_POST['login'];
				$password = $_POST['password'];
				$runame = (new Model_Postclear($_POST['runame']))->clear();
				$user = new Model_User($login, $password, $runame);
				$infouser = $user->setRegisterUser();
				
				if (($infouser->status) == '1')
				{
					$sms->sms = $infouser->msg;
					$sms->login_status = "access_granted";
				}
				else
				{
					$sms->sms = $infouser->msg;
				}
			}
			else
			{
				$sms->sms =  'Поле ' . implode(',', $filterpost) . ' должно быть длиннее 3х символов и содержать только буквы, цифры и -.@_ символы';
			}
		}
		$token = hash('gost-crypto', random_int(0, 999999));
		$_SESSION['tkn'] = $token;
		$this->view->generate('register_view.phtml', 'template_view.phtml', $sms);
	}
}

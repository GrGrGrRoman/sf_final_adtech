<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Postfilter;
use App\models\Model_User;

class Controller_Login extends Controller
{
	public function action_index()
	{
		$data = (object) array();

		if (isset($_POST['tkn']) and $_POST['tkn'] == $_SESSION['tkn'])
		{
			$filter = new Model_Postfilter($_POST);
			$filterpost = $filter->checkPost();
			
			if (!empty($_POST['login']) and !empty($_POST['password']) and !empty($_POST['role']) and empty($filterpost))
			{
				$login = $_POST['login'];
				$password = $_POST['password'];
				$role = $_POST['role'] == 'advert' ? 'advert' : 'master';
				$user = new Model_User($login, $password, '', '');
				$infouser = $user->getCheckUser();
				
				if ($infouser->auth == '1')
				{
					$data->login_status = "access_granted";
					$data->sms = $infouser->sms;
					$_SESSION['auth'] = $infouser->auth;
					$_SESSION['user'] = $infouser->user;
					$_SESSION['runame'] = $infouser->name;
					$_SESSION['userid'] = $infouser->userid;
					$_SESSION['role'] = (!empty($infouser->is_admin)) ? 'admin' : $role;
					header('Location:/admin/');
				}
				else
				{
					$data->sms = $infouser->sms . ' ' . implode(',', $filterpost);
					$data->login_status = "access_denied";
					session_unset();
				}
			}
			else
			{
				$data->login_status = "access_denied";
				$data->sms = 'Поле ' . implode(',', $filterpost) . ' должно быть длиннее 3х символов и содержать только буквы, цифры и -.@_ символы';
			}
		}
		$token = hash('gost-crypto', random_int(0, 999999));
		$_SESSION['tkn'] = $token;
		$this->view->generate('login_view.phtml', 'template_view.phtml', $data);
	}
}

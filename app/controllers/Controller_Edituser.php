<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Edituser;
use App\models\Model_Postclear;
use App\models\Model_Postfilter;
use App\models\Model_User_All;
use App\models\Model_User_one;

class Controller_Edituser extends Controller
{
	public function action_index()
	{
		if (isset($_SESSION['auth']))
		{
			if ($_SESSION['role'] == 'admin')
			{
				$data=(object) array();
				$data->all = (((new Model_User_All())->allUser()));
				$this->view->generate('edituser_view.phtml', 'template_view.phtml', $data);
			}
			else
			{
				header('Location:/edituser/');
			}
		}
		else
		{
			header('Location:/404/');
		}		
	}

	public function action_edit($id)
	{
		$data = (object) array();
		
		if ($_SESSION['role'] == 'admin')
		{
			if (!empty($id))
			{
				$filterid = (new Model_Postfilter(array('id'=>$id)))->checkNum();
				if (!empty($filterid))
				{
					$id = 0;
				}
				else
				{
					$data->one = (new Model_User_one($id))->oneUser();
				}
			}
			else
			{
				$id = 0;
			}
				if (isset($_POST['tkn']) and $_POST['tkn'] == $_SESSION['tkn'])
				{
					if (!empty($_POST['login']) and !empty($_POST['name']) and isset($_POST['balance']) and (!empty($_POST['password']) or (!empty($id))))
					{
					$filterpost = (new Model_Postfilter(array('login'=>$_POST['login'])))->checkPost();
					$filternum = (new Model_Postfilter(array('balance'=>$_POST['balance'])))->checkNum();
					
					if (empty($filterpost) and empty($filternum))
					{
						$login = $_POST['login'];
						$password ='';
						
						if (!empty($_POST['password']))
						{
							$filterpass = (new Model_Postfilter(array('password'=>$_POST['password'])))->checkPost();
							if (empty($filterpass))
							{
								$password =$_POST['password'];
							}
						}

						$balance = $_POST['balance'];
						$name = (new Model_Postclear($_POST['name']))->clear();
						$active = (!empty($_POST['active']) and $_POST['active'] == 1) ? 1 : 0;
						$is_admin = (!empty($_POST['is_admin']) and $_POST['is_admin'] == 1) ? 1 : 0;
						(new Model_Edituser($id, $login, $password, $balance, $active, $is_admin, $name))->edit();
						$data->one=(object) array('id'=>$id,'login'=>$login,'balance'=>$balance,'name'=>$name,'active'=>$active,'is_admin'=>$is_admin);
						$data->status = 'OK';
					}
					else
					{
						$data->status='В полях есть недопустимые символы';
					}
				}
				else
				{
					$data->status= 'Поля не должны быть пустыми';
				}
				}

			$token = hash('gost-crypto', random_int(0,999999));
			$_SESSION['tkn'] = $token;
			$this->view->generate('edituser_view.phtml', 'template_view.phtml', $data);
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function action_delete()
	{
		if (!empty($_POST['del']) and empty((new Model_Postfilter(array('del'=>$_POST['del'])))->checkNum()))
		{
			if (!empty($_SESSION['userid']) and $_SESSION['role'] == 'admin' )
			{
				(new Model_User_one($_POST['del']))->delUser();
				header('Location:/edituser/');
			}
			else
			{
				header('Location:/404/');
			}
		}
		else
		{
			header('Location:/404/');
		}
	}
}
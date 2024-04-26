<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Budget;
use App\models\Model_Postfilter;

class Controller_Budget extends Controller
{
	public function action_index()
	{
		if ($_SESSION['role'] == 'admin')
		{
			$data=(object) array();
			$date = self::getPost('date', 'checkNum()', 0);

			if (!empty($date))
			{
				$date=time()-$date*60*60*24;
			}

			$url = self::getPost('url','checkPost()','');
			$status = self::getPost('status','checkNum()','');
			$masteruserid = self::getPost('masteruserid','checkNum()','');
			$offerid = self::getPost('offerid','checkNum()','');
			$price = self::getPost('price','checkNum()','');
			$page = ((!empty($_POST['page'])) && empty((new Model_Postfilter(array('page'=>$_POST['page'])))->checkNum())) ? $_POST['page'] : 0;
			$itemlimit = 3;
			$budget = (((new Model_Budget($date, $url, $status, $masteruserid, $offerid, $price, $page*$itemlimit, $itemlimit))->getStat()));
			$data->all = $budget->arr;
			$data->balance_platform = $budget->balance_platform;
			$data->balance = $budget->balance;
			$data->count = $budget->count;
			$data->nextpage = ((($page+1)*$itemlimit)>=$data->count) ? '' : ($page+1);
			$data->prevpage = ((($itemlimit)>=$data->count)) ? '' : ($page-1);
			$this->view->generate('budget_view.phtml', 'template_view.phtml', $data);
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function getPost($var, $param, $default)
	{
		if (!empty($_POST[$var]) and empty((new Model_Postfilter(array($var=>$_POST[$var])))->$param))
		{
			return $_POST[$var];
		}
		return $default;			
	}
}
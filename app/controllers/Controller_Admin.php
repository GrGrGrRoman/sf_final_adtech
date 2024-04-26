<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Admin;
use App\models\Model_Offermaster;
use App\models\Model_Offer;
use App\models\Model_Postclear;
use App\models\Model_Postfilter;

class Controller_Admin extends Controller
{
	public function action_index()
	{
		$page=((!empty($_POST['page'])) and empty((new Model_Postfilter(array('page' => $_POST['page'])))->checkNum())) ? $_POST['page'] : 0;
		$offername = (!empty($_POST['offername'])) ? (new Model_Postclear($_POST['offername']))->clear() : '';
		$offertheme =(!empty($_POST['offertheme'])) ? (new Model_Postclear($_POST['offertheme']))->clear() : '';
		$itemlimit = 5;
		$user_active_and_balance_info = (new Model_Admin($_SESSION['user']))->getBlockUser();
		
		if ( $_SESSION['auth'] == "1" and $user_active_and_balance_info->active == 1 )
		{
			switch ($_SESSION['role'])
			{
				case 'master':
					$offertdb = ((new Model_Offermaster('',$_SESSION['userid'],$page*$itemlimit,$itemlimit,$offername,$offertheme))->getOfferAll());
					Controller_Admin::constructRequest($offertdb, 'Мастер', 'offermaster/add', 'Подробнее', $page, $itemlimit, 'search', $user_active_and_balance_info->balance);
				break;

				case 'advert':
					$offertdb = (new Model_Offer($offername, '', $offertheme, '', $_SESSION['userid'], '', '', ($page*$itemlimit), $itemlimit))->getOffer();
					Controller_Admin::constructRequest($offertdb, 'Рекламодатель', 'offer/edit', 'Изменить', $page, $itemlimit, 'search', $user_active_and_balance_info->balance);
				break;

				case 'admin':
					$offertdb = (new Model_Offer($offername, '', $offertheme, '', '', '', '', ($page*$itemlimit), $itemlimit))->getOffer();
					Controller_Admin::constructRequest($offertdb, 'Администратор', 'offer/edit', 'Изменить', $page, $itemlimit, 'index', $user_active_and_balance_info->balance);
				break;
			}
			
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function action_search()
	{
		$user_active_and_balance_info=(new Model_Admin($_SESSION['user']))->getBlockUser();
		if ( $_SESSION['auth'] == "1" and $user_active_and_balance_info->active == 1 )
		{
			$page=((!empty($_POST['page'])) and empty((new Model_Postfilter(array('page'=>$_POST['page'])))->checkNum())) ? $_POST['page'] : 0;
			$itemlimit = 5;
			$offername = (!empty($_POST['offername'])) ? (new Model_Postclear($_POST['offername']))->clear() : '';
			$offertheme =(!empty($_POST['offertheme'])) ? (new Model_Postclear($_POST['offertheme']))->clear() : '';
			$offertdb=(new Model_Offer($offername,'',$offertheme,'','','','',($page*$itemlimit),$itemlimit))->getOffer();
			if($_SESSION['role'] == 'master')
			{
				$nameRole='Мастер';$parturl='offermaster/add';$parurlname='Добавить';
			}
			else
			{
				$nameRole='Рекламодатель';$parturl='offer/edit';$parurlname='Подробнее';
			}
			Controller_Admin::constructRequest($offertdb, $nameRole, $parturl, $parurlname, $page, $itemlimit, 'index', $user_active_and_balance_info->balance);
		}
	}

	private function constructRequest($offertdb, $nameRole, $parturl, $parurlname, $page, $itemlimit, $formaction, $balance)
	{
		$data = (object) array();
		$data->login = $_SESSION['user'];
		$data->table = (!empty($offertdb->item) ? $offertdb->item : '');
		$data->balance = $balance;
		$data->count = (!empty($offertdb->count) ? $offertdb->count : '');
		$data->nameRole = $nameRole;
		$data->parturl = $parturl;
		$data->parturlname = $parurlname;
		$data->formaction = $formaction;
		$data->nextpage = ((($page+1)*$itemlimit)>=$data->count) ? '' : ($page+1);
		$data->prevpage = ((($itemlimit)>=$data->count)) ? '' : ($page-1);
		$this->view->generate('admin_view.phtml', 'template_view.phtml', $data);
	}
	
	public function action_logout()
	{
		session_unset(); 
		session_destroy();
		header('Location:/');
	}
}
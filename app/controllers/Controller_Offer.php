<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Offer;
use App\models\Model_Offerprice;
use App\models\Model_Offercount;
use App\models\Model_Postclear;
use App\models\Model_Postfilter;

class Controller_Offer extends Controller
{
	public function action_index()
	{
		$data=(object) array();

		if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'advert' or $_SESSION['role'] == 'admin'))
		{
			self::offerToDB($data);
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function action_edit($param)
	{
		$data=(object) array();

		if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'advert' or $_SESSION['role'] == 'admin'))
		{
			$datetime = 0;

			if (!empty($_POST['date']))
			{
				switch($_POST['date'])
				{
					case 1:
						$datetime = time()-60*60*24;// за день
					break;

					case 2:
						$datetime = time()-60*60*24*30;// за месяц
					break;

					case 3:
						$datetime = time()-60*60*24*30*12;// за год
					break;

					default: 
						$datetime = 0;
					break;
				}
			}

			$user_id = ($_SESSION['role'] == 'admin') ? '' : $_SESSION['userid'];
			$offerdb = ((new Model_Offer('','','','',$user_id,'',$param))->getOffer())->item;

			if (!empty($offerdb))
			{
				$data->offerurl = $offerdb->url;
				$data->offername = $offerdb->name;
				$data->offertheme = $offerdb->theme;
				$data->offerprice = $offerdb->price;
				
				if ($_SESSION['role'] == 'admin')
				{
					$data->userid = $offerdb->userid;
				}
					$data->offeractive = $offerdb->active;
					$data->offerid = $param;
					$offer_click_price = (new Model_Offerprice ($param,$user_id,0,$datetime))->getOfferbalance();
				if($offer_click_price->status = 1)
				{
					$data->offer_balance = $offer_click_price->balance;
					$data->offer_click = $offer_click_price->count;
				}
				$offer_master_count = (new Model_Offercount($param))->getOfferCount();
				$data->offer_master_count = (!empty($offer_master_count)) ? $offer_master_count : 0;

				self::offerToDB($data);
			}
		}
	}

	private function offerToDB($data)
	{
		$offerid = ((!empty($data->offerid)) ? $data->offerid : '');

	if (isset($_POST['tkn']) and $_POST['tkn'] == $_SESSION['tkn'])
	{
		if (!empty($_POST['offername']) and !empty($_POST['offertheme']) and !empty($_POST['offerurl']) and !empty($_POST['offerprice']))
		{
			if (empty((new Model_Postfilter(array('offerurl'=>$_POST['offerurl'])))->checkUrl()) and empty((new Model_Postfilter(array('offerprice'=>$_POST['offerprice'])))->checkNum()))
			{
				$data->offerurl = $offerurl= $_POST['offerurl'];
				$data->offerprice = $offerprice= $_POST['offerprice'];
				$data->offeractive = $offeractive = (!empty($_POST['offeractive']) and $_POST['offeractive'] == '1') ? '1' : '0';
				$data->offername = $offername = (new Model_Postclear($_POST['offername']))->clear();
				$data->offertheme = $offertheme = (new Model_Postclear($_POST['offertheme']))->clear();
				$data->userid = $userid = ($_SESSION['role'] == 'admin') ? $_POST['userid'] : $_SESSION['userid'];
				$createoffer = new Model_Offer($offername,$offerprice,$offertheme,$offerurl,$userid,$offeractive,$offerid);
				$offer = $createoffer->setOffer();
				$data->msg = $offer->msg;
			}
			else
			{
				$data->msg = 'Поля содержат недопустимые значения';
			}
		}
		else
		{
			$data->msg = 'Заполните все поля';
		}
	}

	$token = hash('gost-crypto', random_int(0,999999));
	$_SESSION['tkn'] = $token;
	$this->view->generate('offer_view.phtml', 'template_view.phtml', $data);	

	}

	public function action_delete()
	{
		if (!empty($_POST['del']) and empty((new Model_Postfilter(array('del'=>$_POST['del'])))->checkNum()))
		{
			if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'advert' or $_SESSION['role'] == 'admin'))
			{
				(new Model_Offercount($_POST['del'],$_SESSION['userid']))->getOfferDelete();
				header('Location:/admin/');							
			}
		}
	}
}
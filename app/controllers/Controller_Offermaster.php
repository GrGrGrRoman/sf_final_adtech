<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Offermaster;
use App\models\Model_Offerprice;
use App\models\Model_Postfilter;

class Controller_Offermaster extends Controller
{	
	public function action_index()
	{
		$data=(object) array();

		if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'master' or $_SESSION['role'] == 'admin'))
		{
			header('Location:/admin/');
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function action_add($param)
	{
		$data=(object) array();

		if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'master' or $_SESSION['role'] == 'admin') and empty((new Model_Postfilter(array('offerid'=>$param)))->checkNum()))
		{
			$datetime = 0;

			if (!empty($_POST['date']))
			{
				switch($_POST['date'])
				{
					case 1:
						$datetime=time()-60*60*24;
					break;

					case 2:
						$datetime=time()-60*60*24*30;
					break;

					case 3:
						$datetime=time()-60*60*24*30*12;
					break;

					default: 
						$datetime=0;
					break;
				}
			}

			$user_id = ($_SESSION['role'] == 'admin') ? '' : $_SESSION['userid'];
			$offerdb = ((new Model_Offermaster($param, $_SESSION['userid']))->getOffer());

			if ($offerdb->status == '1' or $offerdb->status == '2')
			{
				if ($offerdb->status == '1' and $_SESSION['role'] !== 'admin')
				{
					$createoffer = (new Model_Offermaster($param, $_SESSION['userid']))->setOffer();
					$data->msg = $createoffer->msg;
					$data->stat = 'access_granted';
					$data->masterurl = 'http://'.$_SERVER['HTTP_HOST'].'/redirect/url/'.$createoffer->url;
				}
				else
				{
					$data->msg = $offerdb->msg;
					$data->stat = 'access_granted';
					$data->masterurl = 'http://'.$_SERVER['HTTP_HOST'].'/redirect/url/'.$offerdb->offertouser->masterurl;
					$offer_click_price = (new Model_Offerprice ($param, 0, $_SESSION['userid'], $datetime))->getOfferbalance();
					
					if ($offer_click_price->status = 1)
					{
						$data->offer_balance = $offer_click_price->balance;
						$data->offer_click = $offer_click_price->count;
					}
				}
				$data->offerid = $offerdb->offer->id;
				$data->offerprice = $offerdb->offer->price;
				$data->offername = $offerdb->offer->name;
				$data->offerurl = $offerdb->offer->url;
				$data->offeruserid = $offerdb->offer->userid;
				$data->offeradvertname = $offerdb->advertname;
				$data->offertheme = $offerdb->offer->theme;
			}
			else
			{
				$data->stat = 'access_denied';
				$data->msg = $offerdb->msg;
			}
			$this->view->generate('offermaster_view.phtml', 'template_view.phtml', $data);	
		}
		else
		{
			header('Location:/404/');
		}
	}

	public function action_delete()
	{
		$data=(object) array();

		if (!empty($_POST['del']) and empty((new Model_Postfilter(array('del'=>$_POST['del'])))->checkNum()))
		{
			if (!empty($_SESSION['userid']) and ($_SESSION['role'] == 'master' or $_SESSION['role'] == 'admin'))
			{
				(new Model_Offermaster($_POST['del'],$_SESSION['userid']))->getOfferDelete();
				header('Location:/admin/');
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

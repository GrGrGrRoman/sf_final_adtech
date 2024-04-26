<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Model_Offerprice;
use App\models\Model_Postfilter;
use App\models\Model_Redirect;

class Controller_Redirect extends Controller
{
	public function action_index()
	{
		header('Location:/404/');
	}

	public function action_url($url)
	{
		if (!empty($url) and (empty((new Model_Postfilter(array('url' => $url)))->checkPost())))
		{
			$status = 0;
			$offerid = '0';
			$master_user_id = '0';
			$offer_real_url = '';
			$offer_price = 0;
			$search_offer_master = ((new Model_Redirect('', $url))->redirectCheckMaster());
			
			if ($search_offer_master->status == 1)
			{
				$status = 1;
				$offerid = $search_offer_master->offerid;
				$master_user_id = $search_offer_master->master_user_id;
				$search_offer = ((new Model_Redirect($offerid, $url))->redirectCheckOffer());
				if ($search_offer->status == 2)
				{
					if ($search_offer->active == 1)
					{
						$status = 2;
						$offer_real_url = $search_offer->url;
						$userid_answer = $search_offer->userid;
						$status_users_price = ((new Model_Offerprice($offerid, $userid_answer, $master_user_id, ''))->setBalance());
						if ($status_users_price == 5)
						{
							$status = 5;
							$offer_price = $search_offer->price;
						}else
						{
							$status = $status_users_price;
						}
					}
				}
			}
			self::savestatus($offerid, $url, $status, $master_user_id, $offer_real_url, $offer_price);
		}else
		{
			header('Location:/404/');
		}
	}

	public function savestatus($offerid, $url, $status, $master_user_id, $redirect_url, $offer_price)
	{
		$save_offer = ((new Model_Redirect($offerid, $url, $status, $master_user_id, $offer_price))->redirectSave());
		if ($status == 5)
		{
			header('Location:' . $redirect_url);
		}
		else
		{
			header('Location:/404/');
		}
	}
}

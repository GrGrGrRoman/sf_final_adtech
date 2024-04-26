<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Redirect extends Model
{
	private $master_user_id;
	private $url;
	private $status;
	private $offerid;
	private $offer_price;

	public function __construct($offerid = '0', $url, $status = '0', $master_user_id = '0', $offer_price = '0')
	{
		  $this->master_user_id = $master_user_id;
		  $this->url = $url;
		  $this->status = $status;
		  $this->offerid = $offerid;
		  $this->offer_price = $offer_price;
	}

	public function redirectSave()
	{
		$offeradd = (object) array();
		$offeradd = R::dispense('click');
		$offeradd->date = time();
		$offeradd->masteruserid = $this->master_user_id;
		$offeradd->status = $this->status;
		$offeradd->url = $this->url;
		$offeradd->price = $this->offer_price;
		$offeradd->offerid = $this->offerid;
		R::store($offeradd);
	}

	public function redirectCheckMaster()
	{
		$search_offer = (object) array();
		$get_master_offer = R::findOne('masteroffer', "masterurl = ?", [$this->url]);
		
		if (!empty($get_master_offer))
		{
			$search_offer->master_user_id = $get_master_offer->userid;
			$search_offer->offerid = $get_master_offer->offerid;
			$search_offer->status = 1;
			return $search_offer;
		}
	}

	public function redirectCheckOffer()
	{
		$search_offer = (object) array();
		$get_master_offer = R::findOne('adoffer', "id = ?", [$this->offerid]);
		
		if (!empty($get_master_offer))
		{
			$search_offer->price = $get_master_offer->price;
			$search_offer->active = $get_master_offer->active;
			$search_offer->url = $get_master_offer->url;
			$search_offer->userid = $get_master_offer->userid;
			$search_offer->status = 2;
			return $search_offer;
		}
	}
}
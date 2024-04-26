<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Offerprice extends Model
{
	private $id;
	private $user_id;
	private $master_user_id;
	private $date;

	public function __construct($offer_id, $user_id = 0, $master_user_id = 0, $date = 0)
		{
		  $this->id = $offer_id;
		  $this->user_id = $user_id;
		  $this->master_user_id = $master_user_id;
		  $this->date = $date;
		}
		
	public function setBalance()
	{
		$price_offer = Model_Offerprice::getOfferPrice();
		
		if (!empty($price_offer->active == 1))
		{
			$get_user_ad = R::findOne('users', "id = ?", [$this->user_id]);

			if (!empty($get_user_ad->active))
			{
				$get_user_ad->balance = ($get_user_ad->balance) - ($price_offer->price);// рассчет за переход
					
				if ($get_user_ad->balance < 0)
					{// баланс пустой
						return 0;
					}

					if ($this->user_id == $this->master_user_id)
					{// удержание 25% комиссии за действие мастера с собственным оффером без оплаты
						$get_user_ad->balance = ($get_user_ad->balance) + ($price_offer->price / 100 * 75);
						R::store($get_user_ad);
						return 5;
					}

					$get_user_master = R::findOne('users', "id = ?",[$this->master_user_id]);
					
					if (!empty($get_user_master->active))
					{
						$get_user_master->balance = ($get_user_master->balance) + ($price_offer->price / 100 * 80);// оплата мастеру за переход минус 20% комиссия
						R::store($get_user_ad);
						R::store($get_user_master);
						return 5; // операция прошла успешно
					}
					return 4;// мастер заблокирован
			}
			return 3;// рекламодатель заблокирован
		}
		return 2;// оффер не активный
	}

	public function getOfferbalance()
	{
		$offerbalance = (object) array();
		$getofferprice = (object) array();

		if (!empty($this->master_user_id))
		{
			$getoffercount = R::count('click', "masteruserid= ? and offerid = ? and status = ? and date > ? ", [$this->master_user_id, $this->id, 5, $this->date]);
			$getofferprice = (int)R::getCell('SELECT SUM(price) FROM click WHERE masteruserid = ? and offerid = ? and status = ? and date > ? ', [$this->master_user_id, $this->id, 5, $this->date])/100*80;
		}
		else
		{
			$getoffercount = R::count('click', "offerid = ? and status = ? and date > ? ", [$this->id, 5, $this->date]);
			$getofferprice = (int)R::getCell('SELECT SUM(price) FROM click WHERE offerid = ? and status = ? and date > ? ', [$this->id, 5, $this->date]);
		}
		$offerbalance->status = 1;
		$offerbalance->count = $getoffercount;
		$offerbalance->balance = $getofferprice;
		return $offerbalance;
	}

	public function getOfferPrice()
	{
		$get_offer_price_return = (object) array();
		$get_offer_price = R::findOne('adoffer', "id = ?", [$this->id]);
		
		if (!empty($get_offer_price))
		{
			$get_offer_price_return->price = $get_offer_price->price;
			$get_offer_price_return->active = $get_offer_price->active;
			return $get_offer_price_return;
		}
	}
}

<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Budget extends Model
{

	private $date;
	private $url;
	private $status;
	private $masteruserid;
	private $offerid;
	private $price;
	private $page;
	private $limit;

	public function __construct($date = 0, $url = '', $status = '', $masteruserid = '', $offerid = '', $price = '', $page = 0, $limit = 5)
	{
		$this->date = $date;
		$this->url = $url;
		$this->status = $status;
		$this->masteruserid = $masteruserid;
		$this->offerid = $offerid;
		$this->price = $price;
		$this->page = $page;
		$this->limit = $limit;
	}

	public function getStat()
	{
		$offerbalance = (object) array();
		$getofferprice = (object) array();
		$arr_offer_click = (object) array();
		$request[] = " date > $this->date ";
		
		if (!empty($this->url))
		{
			$request[] = " url = '$this->url' ";
		}

		if (!empty($this->status))
		{
			$request[] = " status = $this->status ";
		}

		if (!empty($this->masteruserid))
		{
			$request[] = " masteruserid = $this->masteruserid ";
		}

		if (!empty($this->offerid))
		{
			$request[] = " offerid = $this->offerid ";
		}

		if(!empty($this->price))
		{
			$request[] = " price >= $this->price ";
		}

		$dbrequest = implode(' and ', $request);
		$getoffercount = R::count('click', "$dbrequest");
		$getofferprice = (int)R::getCell("SELECT SUM(price) FROM click WHERE $dbrequest ");
		$arr_offer_click = R::findAll('click', "$dbrequest  ORDER BY id DESC  LIMIT $this->page , $this->limit");
		$offerbalance->count = $getoffercount;
		$offerbalance->balance_platform = $getofferprice/100*20;
		$offerbalance->balance = $getofferprice;
		$offerbalance->arr = $arr_offer_click;
		return $offerbalance;
	}
}

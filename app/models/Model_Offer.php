<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Offer extends Model
{
	private $name;
	private $price;
	private $theme;
	private $url;
	private $userid;
	private $id;
	private $active;
	private $page;
	private $limit;

	public function __construct($offername, $offerprice, $offertheme, $offerurl, $userid, $active, $offerid = '', $page = 0, $limit = 5)
	{
		  $this->name = $offername;
		  $this->price = $offerprice;
		  $this->theme = $offertheme;
		  $this->url = $offerurl;
		  $this->userid = $userid;
		  $this->id = $offerid;
		  $this->active = $active;
		  $this->page = (int)$page;
		  $this->limit = (int)$limit;
	}

	public function setOffer()
	{		
		$stat=(object) array();
		$offeradd=(object) array();
		$offeradd = R::dispense('adoffer');
		if(!empty($this->id)){$offeradd->id = $this->id;}
		$offeradd->name = $this->name;
		$offeradd->theme = $this->theme;
		$offeradd->price = $this->price;
		$offeradd->url = $this->url;
		$offeradd->active = $this->active;
		$offeradd->date = time();

		if(R::findOne('users', 'id='.$this->userid))
		{
			$offeradd->userid = $this->userid;
			R::store($offeradd);
			$stat->status = '1';
			$stat->msg = 'Выполнено';
			return $stat;
		}
		else
		{
			$stat->status = '0';
			$stat->msg = 'Пользователь не найден';
			return $stat;
		}
	}

	public function getOffer()
	{
		$paramtodb = array();
		$requesttodb = '';
		$getoffer=(object) array();

		foreach ($this as $key=>$val)
		{
			if (!empty($val))
			{
				if ($key == 'page' or $key == 'limit')
				{
					continue;
				}
				if ($key == 'userid' or $key == 'id')
				{
					$paramtodb[] = "$key = '$val'";
				}
				else
				{
					$paramtodb[] = "$key LIKE '%$val%'";
				}
			}
		}

		$requesttodb = implode(' and ', $paramtodb);
		
		if (!empty($this->id))
		{
			$getoffer->item = R::findOne('adoffer', $requesttodb);
		}
		else
		{
			$getoffer->item = R::findAll('adoffer', $requesttodb . ' ORDER BY id DESC  LIMIT '.($this->page).', ' .$this->limit );
			$getoffer->count = R::count('adoffer', $requesttodb);
		}

		if ($getoffer->item)
		{
			return $getoffer;
		}
		return false;
	}
}
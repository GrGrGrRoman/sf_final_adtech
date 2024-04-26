<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Offermaster extends Model
{
	private $id;
	private $userid;
	private $page;
	private $offer_name;
	private $offer_theme;
	private $limit;

	public function __construct($offerid, $userid, $page='0', $limit = 5, $offer_name='', $offer_theme='')
	{
		  $this->id = $offerid;
		  $this->userid = $userid;
		  $this->page = $page;
		  $this->offer_name = $offer_name;
		  $this->offer_theme = $offer_theme;
		  $this->limit = $limit;
	}

	public function setOffer()
	{		
		$stat = (object) array();
		$offeradd = (object) array();
		$offeradd = R::dispense('masteroffer');
		$offeradd->offerid = $this->id;
		$offeradd->userid = $this->userid;
		$offeradd->masterurl = hash('gost-crypto', random_int(0,999999));
		R::store($offeradd);
		$stat->url = $offeradd->masterurl;
		$stat->status = '1';
		$stat->msg = 'Вы подписались на оффер';
		return $stat;
	}

	public function getOffer()
	{
		$get_offer = R::findOne('adoffer', "id = ?", [$this->id]);
		$get_offer_master = R::findOne('masteroffer', "offerid = ? and userid = ?", [$this->id,$this->userid]);

		if (!empty($get_offer) and ($get_offer->active == '1'))
		{
			$get_offerr_advert = R::findOne('users', "id = ?", [$get_offer->userid]);

			if ($get_offerr_advert->active == 1)
			{			
				if (empty($get_offer_master))
				{
					return Model_Offermaster::getStatusOffer($get_offerr_advert->name, 'Оффер найден и активен', $get_offer, '1', '');// 1 - оффер свободен
				}
				return Model_Offermaster::getStatusOffer($get_offerr_advert->name, 'Вы подписаны на этот оффер', $get_offer, '2', $get_offer_master);// 2 - оффер уже в списке мастера
			}
			return Model_Offermaster::getStatusOffer($get_offerr_advert->name, 'Создатель оффера заблокирован!', '', '0', '');// 0 - оффер не активный
		}
		return Model_Offermaster::getStatusOffer('', 'Оффер не активный или несуществует!', '', '0', '');// 0 - оффер не активный
	}

	public function getOfferAll()
	{
		$offerid = [];
		$get_offer = (object) array();
		$get_offer_master = R::find('masteroffer', "userid = $this->userid");

		foreach ($get_offer_master as $val)
		{
			$offerid[] = $val->offerid;
		}

		$offeridstr = implode(',', $offerid);

		if (!empty($offeridstr))
		{
			$like = '';

			if (!empty($this->offer_name))
			{
				$like.=' AND name like '.$this->offer_name;
			}

			if (!empty($this->offer_theme))
			{
				$like.=' AND theme like '.$this->offer_theme;
			}

			$get_offer->item = R::find('adoffer', 'id IN ('.$offeridstr.') '.$like.'  ORDER BY id DESC  LIMIT '.($this->page).','.$this->limit);
			$get_offer->count = R::count('adoffer', 'id IN ('.$offeridstr.')');
		}
		if ($get_offer)
		{
			return $get_offer;
		}
		return false;
	}

	public function getOfferDelete()
	{
		$find = R::findOne('masteroffer', 'offerid = ? and userid = ?', [$this->id, $this->userid]);
		$delete = R::load('masteroffer', $find->id);
		R::trash($delete);
	}

	private function getStatusOffer($advertname, $msg, $get_offer, $status, $get_offer_master)
	{
		$offer = (object) array();
		$offer->advertname = $advertname;
		$offer->msg = $msg;
		$offer->offer = $get_offer;
		$offer->status = $status;
		$offer->offertouser = $get_offer_master;
		return $offer;
	}
}
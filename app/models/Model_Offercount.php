<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Offercount extends Model
{
	private $id;
	private $userid;
	
	public function __construct($offerid, $userid = '')
	{
		$this->id = $offerid;
		$this->userid = $userid;
	}

	public function getOfferCount()
	{
		$find = R::count('masteroffer', 'offerid = ? ', [$this->id ]);
		return $find;
	}

	public function getOfferDelete()
	{
		$find_id = (object) array();
		$find = R::findOne('adoffer', 'id = ? and userid = ?', [$this->id, $this->userid]);
		$find_id = (!empty($find->id)) ? $find->id : '';
	    
		if ($_SESSION['role'] == 'admin')		
		{
			$find_id = $this->id;
		}
		$delete = R::load('adoffer', $find_id);
	    R::trash($delete);
	}
}
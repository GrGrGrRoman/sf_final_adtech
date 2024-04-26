<?php

namespace App\models;

use App\core\Model;
use R;

class Model_User_one extends Model
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function oneUser()
	{
		$userAcc = R::findOne('users',"id = $this->id");
		return $userAcc;
	}

	public function delUser()
	{
		$delete = R::load('users', $this->id);
		R::trash($delete);
	}
}

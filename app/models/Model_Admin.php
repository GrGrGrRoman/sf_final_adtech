<?php

namespace App\models;

use App\core\Model;
use R;

class Model_Admin extends Model
{
	private $user;

	public function __construct($user)
	{
		$this->user = $user;
	}

	public function getBlockUser()
	{	
		$userAcc = R::findOne('users', "login = ? ", [$this->user]);
		if ($userAcc->active == 1)
		{
			$userAcc->datetime = time();
			$userAcc->ip = $_SERVER['REMOTE_ADDR'];
			R::store($userAcc);
			return $userAcc;
		}
	}
}

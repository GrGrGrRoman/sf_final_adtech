<?php

namespace App\models;

use App\core\Model;
use R;

class Model_User_All extends Model
{
	public function allUser()
	{
		$userAcc = R::findAll('users');
		return $userAcc;
	}
}
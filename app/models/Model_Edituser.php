<?php

namespace App\models;

use App\core\Model;
use R;

use function App\core\config\dd;

class Model_Edituser extends Model
{
	private $login;
	private $name;
	private $is_admin;
	private $id;
	private $password;
	private $balance;
	private $active;
	private $ip;
	private $datetime;

	public function __construct($id, $login, $password, $balance, $active, $is_admin, $name)
	{
		$this->login = $login;
		$this->password = $password;
		$this->name = $name;
		$this->is_admin = $is_admin;
		$this->id = $id;
		$this->password = $password;
		$this->balance = $balance;
		$this->active = $active;
	}

	public function edit()
	{
		$user = R::dispense('users');

		if (!empty($this->id))
		{
			$user->id = $this->id;
			$user->password = (new Model_User_one($this->id))->oneUser()->password;			
		}

		$user->login = $this->login;

		if (!empty($this->password))
		{
			$user->password = password_hash($this->password,PASSWORD_DEFAULT);
		}

		$user->balance = $this->balance;
		$user->name = $this->name;
		$user->active = $this->active;
		$user->is_admin = $this->is_admin;
		R::store($user);
	}
}
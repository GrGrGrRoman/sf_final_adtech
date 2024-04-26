<?php

namespace App\models;

use App\core\Model;

class Model_Postclear extends Model
{
	private $post;

	public function __construct($post)
	{
		$this->post = $post;
	}

	public function clear()
	{
		$clear = preg_replace("/[^\p{L}0-9\!\.\-_\s\?,:*\+;\(\)]/ui", "", $this->post);
		return $clear;
	}
}
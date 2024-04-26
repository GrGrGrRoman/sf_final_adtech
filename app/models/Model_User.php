<?php

namespace App\models;

use R;

class Model_User
{
  private $user;
  private $pass;
  private $name;
  private $is_admin;

  public function __construct($user, $pass, $name, $is_admin = 0)
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->name = $name;
    $this->is_admin = $is_admin;
  }

  public function setRegisterUser()
  {
    $stat = (object) array();
    if (Model_User::getCheckUser()->found == '1')
    {
      $stat->status = '0';
      $stat->msg = 'Такой пользователь уже зарегистрирован';
      return $stat;
    }
    else
    {
      $user = R::dispense('users');
      $user->login = $this->user;
      $user->password = password_hash($this->pass, PASSWORD_DEFAULT);
      $user->balance = '0';
      $user->is_admin = $this->is_admin;
      $user->ip = $_SERVER['REMOTE_ADDR'];
      $user->datetime = time();
      $user->name = $this->name;
      R::store($user);
      $stat->status = '1';
      $stat->msg = 'Новый пользователь зарегистрирован, теперь администратор должен его активировать';
      return $stat;
    }
  }

  public function getCheckUser()
  {
    $userAcc = R::findOne('users', "login = ? ", [$this->user]);
    if ($userAcc)
    {
      if (password_verify($this->pass, $userAcc->password))
      {
        if ($userAcc->active == 1)
        {
          if ($userAcc->is_admin == 1)
          {
            return Model_User::returnRequest($userAcc->login, $userAcc->id, true, true, 'Авторизация прошла успешно', $userAcc->name, $userAcc->is_admin);
          }
          return Model_User::returnRequest($userAcc->login, $userAcc->id, true, true, 'Авторизация прошла успешно', $userAcc->name);
        }
        return Model_User::returnRequest('', '', true, false, 'Пользователь заблокирован, обратитесь к администратору', '');
      }
      return Model_User::returnRequest('', '', true, false, 'Неверный логин или пароль', '');
    }
    return Model_User::returnRequest('', '', false, false, 'Пользователь не найден', '');
  }

  private function returnRequest($user, $userid, $found, $auth, $sms, $name, $is_admin = 0)
  {
    $userdb = (object) array();
    $userdb->user = $user;
    $userdb->is_admin = $is_admin;
    $userdb->userid = $userid;
    $userdb->name = $name;
    $userdb->found = $found;
    $userdb->auth = $auth;
    $userdb->sms = $sms;
    return $userdb;
  }

  static public function checkAuth(): bool
  {
    return isset($_SESSION['auth']);
  }
}

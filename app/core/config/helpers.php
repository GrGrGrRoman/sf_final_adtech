<?php

namespace App\core\config;

function d($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function print_arr($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function dd($data)
{
    d($data);
    die;
}

function abort($code = 404, $title = '404 - Not found')
{
    http_response_code($code);
    echo 'ERROR 404';
    require VIEW . 'layouts/404_view.phtml';
    die;
}

function load($fillable = [])
{
    $data = [];
    foreach ($_POST as $k => $v) {
        if (in_array($k, $fillable)) {
            $data[$k] = trim($v);
        }
    }
    return $data;
}

function old($fieldname)
{
    return isset($_POST[$fieldname]) ? h($_POST[$fieldname]) : '';
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function redirect($url = '')
{
    if ($url) {
        $redirect = $url;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'localhost';
    }
    header("Location: {$redirect}");
    die;
}

function get_alerts()
{

}
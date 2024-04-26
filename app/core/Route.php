<?php

namespace App\core;

class Route
{
	public static function start()
	{
		$controller_name = 'Main';
		$action_name = 'index';
		$action_param = '';
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		if (!empty($routes[1]))
		{
			$controller_name = $routes[1];
		}

		if (!empty($routes[2]))
		{
			$action_name = $routes[2];
		}
		
		if (!empty($routes[3]))
		{
			$action_param = $routes[3];
		}
		
		$model_name = 'model_' . $controller_name;
		$controller_name = CONTROLLERS_NAMESPACE . 'Controller_' . $controller_name;
		$action_name = 'action_' . $action_name;
		$model_file = strtolower($model_name) . '.php';
		$model_path = MODEL . DIRECTORY_SEPARATOR . $model_file;
		
		if (file_exists($model_path))
		{
			include MODEL . DIRECTORY_SEPARATOR . $model_file;
		}

		$controller_file = ucfirst($controller_name) . '.php';
		
		if (file_exists($controller_file))
		{
			include $controller_file;
		}
		else
		{
			self::ErrorPage404();
		}

		$controller = new $controller_name();
		$action = $action_name;

		if (method_exists($controller, $action))
		{
			$action_param = preg_replace("/[^-._А-Яа-я\w]+$/ui", '', $action_param);
			$controller->$action($action_param);
		}
		else
		{
			self::ErrorPage404();
		}
	}

	public static function ErrorPage404()
	{
		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		header('Location:' . $host . '404');
	}
}

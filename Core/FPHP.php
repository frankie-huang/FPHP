<?php
namespace Core;

class FPHP{
	public static $classMap = array();

	static public function run(){
		// 当调用未定义的类的时候自动加载该类
		spl_autoload_register("Core\FPHP::load");

		// 加载配置文件
		include "Conf/config.php";
		
		if (!isset($_SERVER['PATH_INFO']) || empty($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO'] == '/') {
			$match = array(
				'/Home/Index/index',
				'Home',
				'Index',
				'index',
			);
		} else {
			$PATH_INFO = $_SERVER['PATH_INFO'];
			$count = preg_match('/\/(\w+)\/(\w+)\/(\w+)/', $PATH_INFO, $match);
			if ($count != 1) {

			}
		}
		
		
		$class = '\App\\' . $match[1] . '\Controller\\'. $match[2];
		$operation = $match[3];
		$Controller = new $class();
		$Controller->$operation();
	}

	static function load($class) {
		if (isset(self::$classMap[$class])){
			return true;
		} else {
			$class = str_replace('\\', '/', $class);
			$file = FPHP . '/' . $class . '.php';
			if (is_file($file)) {
				include $file;
				self::$classMap[$class] = $file;
			} else {
				return false;
			}
		}
	}
}
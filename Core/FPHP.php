<?php
namespace Core;

class FPHP
{

    public static $classMap = array();

    public static function run()
    {
        // 当调用未定义的类的时候自动加载该类
        spl_autoload_register("Core\FPHP::load");

        /**
         * 此处是针对nginx不支持PATH_INFO的处理
         * 当PATH_INFO读取不到的时候使用$_SERVER['REQUEST_URI']截取获得
         * $_SERVER['REQUEST_URI']后面会带query_string需要去除
         */
        $URL_prefix_length = strlen($_SERVER['SCRIPT_NAME']) - 9;
        if (!isset($_SERVER['PATH_INFO']) || empty($_SERVER['PATH_INFO'])) {
            if (strlen($_SERVER['REQUEST_URI']) <= $URL_prefix_length) {
                $_SERVER['PATH_INFO'] = '/';
            } else {
                $_SERVER['PATH_INFO'] = substr($_SERVER['REQUEST_URI'], $URL_prefix_length - 1);
                preg_match('/[^?#]*/', $_SERVER['PATH_INFO'], $match); // 截取 ? 和 # 号之前的字符串
                $_SERVER['PATH_INFO'] = $match[0];
            }
        }

        // 处理PATH_INFO，加载对应模块-控制器-操作
        if ($_SERVER['PATH_INFO'] == '/') {
            // $match = array(
            //     '/Home/Index/index',
            //     'Home',
            //     'Index',
            //     'index',
            // );
            header("Location: ./" . DEFAULT_MODULE . "/Index/index");
            exit;
        } else {
            $PATH_INFO = $_SERVER['PATH_INFO'];
            $count = preg_match('/\/(\w+)\/(\w+)\/(\w+)(.*)/', $PATH_INFO, $match);
            if ($count != 1) {
                http_response_code(404);
                header('Content-Type: text/html; charset=UTF-8');
                printError('URL格式：/模块/控制器/操作，示例：/' . DEFAULT_MODULE . '/Index/index');
                return false;
            }
        }
		$action = $match[3];
        $class = '\App\\' . $match[1] . '\Controller\\' . $match[2];
        if (!class_exists($class)) {
            http_response_code(404);
            header('Content-Type: text/html; charset=UTF-8');
            printError('无法加载控制器: ' . $match[2]);
            return false;
        }
        if ($match[4] == '' || $match[4] == '/') {
            $Controller = new $class($match[1], $match[2], $match[3]);
        } else {
            $match[4] = substr($match[4], 1);
            $params = explode('/', $match[4]);
            $Controller = new $class($match[1], $match[2], $match[3], $params);
        }
        if (!method_exists($Controller, $action)) {
            http_response_code(404);
            header('Content-Type: text/html; charset=UTF-8');
            printError('非法操作: ' . $action);
            return false;
        }
        $Controller->$action();
    }

    /**
     * 当调用一个类时，调用load函数加载类文件
     */
    public static function load($class)
    {
        if (isset(self::$classMap[$class])) {
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

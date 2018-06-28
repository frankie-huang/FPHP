<?php
/**
 * 入口文件 定义常量 加载所需的函数库 启动框架
 * 此入口文件只能修改DEBUG模式
 * @author Frankie
 * @link https://github.com/frankie-huang/FPHP
 */

// 定义当前框架所在的根目录
define("FPHP", realpath("./"));

// 定义框架的核心文件所在的目录
define("CORE", FPHP . "/Core");

// 定义项目文件的所处目录
define("APP", FPHP . "/App");

// 定义默认模块
define("DEFAULT_MODULE", "Home");

// 定义是否开启调试模式
define("DEBUG", true);

// 判断是否开启调试模式
if (DEBUG) {
    // 如果开启了debug、则显示debug的相关信息
    ini_set("display_error", "On");
} else {
    // 否则就关掉错误调试
    ini_set("display_error", "Off");
}

// 加载配置文件
$GLOBALS['config'] = require CORE . "/Conf/config.php";

// 加载函数库
require CORE . "/Common/function.php";

// 加载框架的核心文件
require CORE . "/FPHP.php";

Core\FPHP::run();

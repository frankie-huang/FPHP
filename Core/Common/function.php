<?php

/**
 * C函数，用于读取框架配置
 * @param string 配置信息对应的键名
 */
function C($key) {
    return $GLOBALS['config'][$key];
}

/**
 * M函数
 * 用于初始化一个数据库连接
 * @param string $dbtable 数据表名
 * @param int $ConfigID 数据库配置的索引，用于指定配置文件中对应的数据库配置
 * @param mixed $dbConfig 数据库配置，用于自定义数据库配置
 */
function M($dbtable, $ConfigID = 0, $dbConfig = null)
{
    return new Core\lib\PDOMySQL($dbtable, $ConfigID, $dbConfig);
}

/**
 * I函数
 * 暂时仅支持GET和POST
 * @param string $str
 */
function I($str)
{
    $pos = strrpos($str, '.', -1);
    if ($pos === false) {
        printError("I函数参数错误");
        return false;
    }
    $type = substr($str, 0, $pos);
    $param = substr($str, $pos + 1);
    switch (strtoupper($type)) {
        case 'GET':
            if ($param != '') {
                $result_set = $_GET[$param];
            } else {
                $result_set = $_GET;
            }
            break;
        case 'POST':
            // 如果$_POST中无数据，则从php://input中取
            if (count($_POST) == 0) {
                $_POST = json_decode(file_get_contents('php://input'), true);
            }
            if ($param != '') {
                $result_set = $_POST[$param];
            } else {
                $result_set = $_POST;
            }
            break;
        default:
            printError("I函数不支持此参数：" . $str);
            return false;
    }
    if (is_array($result_set)) {
        array_walk_recursive($result_set, function (&$value) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        });
    }
    return $result_set;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * Ajax方式返回数据到客户端
 * 暂时只支持返回json格式数据
 */
function ajaxReturn($data)
{
    header('Content-Type:application/json; charset=utf-8');
    $data = json_encode($data);
    exit($data);
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo ($output);
        return null;
    } else {
        return $output;
    }
}

/**
 * 打印错误信息
 * @param string $errMsg 详细错误信息
 * @param boolean $debug_mode 是否DEBUG模式
 * @return void
 */
function printError($errMsg, $debug_mode = true)
{
    if ($debug_mode && DEBUG) {
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        $errMsg .= '</b><br/><br/><b>SOURCE</b><br>FILE: ' . $caller['file'] . '   LINE: ' . $caller['line'];
        $caller = array_shift($bt);
        $number = 0;
        if ($caller != null) {
            $errMsg .= '<br/><br/><b>TRACE</b><br/>';
        }
        while ($caller != null) {
            $number++;
            $errMsg .= '#' . $number . ' ' . $caller['file'] . '(' . $caller['line'] . ')<br/>';
            $caller = array_shift($bt);
        }
    } else {
        $errMsg = "系统出错，请联系管理员。</b>";
    }
    echo '<div style="width:80%;background-color:#ABCDEF;color:black;padding:20px 0px;"><b style="font-size:25px;">
            ' . $errMsg . '
    </div><br/>';
}

/**
 * PHP的html编码函数
 */
function html_encode($str)
{
    $s = "";
    if (strlen($str) == 0) {
        return "";
    }
    $s = preg_replace('/&/', "&amp;", $str);
    $s = preg_replace('/</', "&lt;", $s);
    $s = preg_replace('/>/', "&gt;", $s);
    $s = preg_replace('/ /', "&nbsp;", $s);
    $s = preg_replace('/\'/', "&#39;", $s);
    $s = preg_replace('/\"/', "&quot;", $s);
    $s = preg_replace('/\n/', "<br/>", $s);
    return $s;
}

/**
 * PHP的html解码函数
 */
function html_decode($str)
{
    $s = "";
    if (strlen($str) == 0) {
        return "";
    }
    $s = preg_replace('/&lt;/', "<", $str);
    $s = preg_replace('/&gt;/', ">", $s);
    $s = preg_replace('/&nbsp;/', " ", $s);
    $s = preg_replace('/&#39;/', "\'", $s);
    $s = preg_replace('/&quot;/', "\"", $s);
    $s = preg_replace('/&amp;/', "&", $s);
    $s = preg_replace('/<br\/>/', "\n", $s);
    return $s;
}

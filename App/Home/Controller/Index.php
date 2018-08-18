<?php
namespace App\Home\Controller;
use Core\lib\Controller;

class Index extends Controller
{
    // 如果子类要定义自己的构造函数，需要这样写👇
    public function __construct($module, $controller, $action, $params = array())
    {
        parent::__construct($module, $controller, $action, $params);
        /**
         * 其他操作
         */
    }

    public function index()
    {
        $this->display();
    }
}

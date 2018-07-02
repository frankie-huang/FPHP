<?php
namespace Core\lib;

abstract class Controller
{
    /**
     * 当前模块名称
     */
    protected $_module = '';

    /**
     * 当前控制器名称
     */
    protected $_controller = '';

    /**
     * URL指定的操作名称
     */
    protected $_action = '';

    /**
     * URL中在action后面的路径参数
     * 比如访问路径为: 'Home/Index/index/a/b/c'
     * 则 $_param 为 array('a', 'b', 'c')
     */
    protected $_param = array();

    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    /**
     * 构造函数 初始化模板对象实例
     * @access public
     */
    public function __construct($module, $controller, $action, $param = array())
    {
        $this->_module = $module;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_param = $param;
        //实例化视图类
        $this->view = new View($module, $controller, $action);
    }
}

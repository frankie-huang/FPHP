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
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    /**
     * 构造函数 初始化模板对象实例
     * @access public
     */
    public function __construct($module, $controller, $action)
    {
        $this->_module = $module;
        $this->_controller = $controller;
        $this->_action = $action;
        //实例化视图类
        $this->view = new View($module, $controller, $action);
    }
}

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
     * 则 $_params 为 array('a', 'b', 'c')
     */
    protected $_params = array();

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
    public function __construct($module, $controller, $action, $params = array())
    {
        $this->_module = $module;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_params = $params;
        //实例化视图类
        $this->view = new View($module, $controller, $action);
    }

    /**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
    }

    /**
     * 渲染页面
     */
    public function display()
    {
        $this->view->display();
    }

    /**
     * Ajax方式返回数据到客户端
     * 暂时只支持返回json格式数据
     */
    public function ajaxReturn($data)
    {
        header('Content-Type:application/json; charset=utf-8');
        $data = json_encode($data);
        exit($data);
    }
}

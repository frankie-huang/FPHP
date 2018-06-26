<?php
namespace Core\lib;

/**
 * FPHP 视图类
 */
class View
{
    /**
     * 当前控制器名称
     */
    protected $_controller = '';

    /**
     * URL指定的操作名称
     */
    protected $_action = '';

    /**
     * 模板输出变量
     * @var tVar
     * @access protected
     */
    protected $tVar = array();

    /**
     * 构造函数
     */
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    /**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->tVar = array_merge($this->tVar, $name);
        } else {
            $this->tVar[$name] = $value;
        }
    }

    /**
     * 取得模板变量的值
     * @access public
     * @param string $name
     * @return mixed
     */
    public function get($name = '')
    {
        if ('' === $name) {
            return $this->tVar;
        }
        return isset($this->tVar[$name]) ? $this->tVar[$name] : false;
    }

    /**
     * 加载模板和页面输出
     * @access public
     */
    public function display()
    {
        extract($this->tVar);
        $file_name = './App/Home/View/' . $this->_controller . '/' . $this->_action . '.html';
        include $file_name;
    }
}
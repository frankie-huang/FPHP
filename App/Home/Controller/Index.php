<?php
namespace App\Home\Controller;
use Core\lib\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->view->display();
    }
}

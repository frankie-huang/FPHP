<?php
namespace App\Home\Controller;

class Index
{
    public function index()
    {
        $db = M('test');
        dump($db->select());
    }
}

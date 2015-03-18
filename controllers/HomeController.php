<?php

class HomeController {

    public function index()
    {
        require_once('views/home.php');
    }

    public function profile()
    {
        require_once('views/profile.php');
    }

    public function friends()
    {
        echo 'hello friends';
    }
}
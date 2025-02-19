<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return $this->twig->render('dashboard/index');
    }
}

<?php

namespace App\Controllers;

use App\Models\CodeGeneratorModel;
use App\Models\GuestModel;

class Guest extends BaseController
{
    protected $CodeGeneratorModel;
    protected $model;

    public function __construct()
    {
        $this->CodeGeneratorModel = new CodeGeneratorModel();
        $this->model = new GuestModel();
    }

    public function index()
    {
        return $this->twig->render('guest/index');
    }

    public function ajaxList()
    {
        $lists = $this->model->getData();

        return $this->response->setJSON($lists);
    }
}

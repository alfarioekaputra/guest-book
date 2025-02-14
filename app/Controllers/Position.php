<?php

namespace App\Controllers;

use App\Models\PositionModel;

class Position extends BaseController
{
  protected $model;

  public function __construct()
  {
    $this->model = new PositionModel();
  }
  public function index()
  {
    return $this->twig->render('position/index');
  }

  public function ajaxList()
  {
    $lists = $this->model->getData();

    return $this->response->setJSON($lists);
  }

  public function create()
  {
    //
  }
}

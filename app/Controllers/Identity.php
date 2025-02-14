<?php

namespace App\Controllers;

use App\Models\IdentityModel;

class Identity extends BaseController
{
  protected $model;

  public function __construct()
  {
    $this->model = new IdentityModel();
  }
  public function index()
  {
    return $this->twig->render('identity/index');
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

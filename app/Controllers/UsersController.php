<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use Config\Services;

class UsersController extends Controller
{
  protected $request;
  protected $model;

  public function __construct()
  {
    $this->request = Services::request();
    $this->model = new UserModel($this->request);
  }

  public function index()
  {
    //get all users
    // $users = $this->userModel->findAll();

    //send data to view
    return view('users/index');
  }

  public function ajaxList()
  {
    $request = \Config\Services::request();
    $list_data = $this->model;
    $where = ['active =' => 1];
    //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    $column_order = array('users.username', 'auth_identities.secret');
    $column_search = array('users.username');
    $order = array('users.username' => 'asc');
    $lists = $list_data->get_datatables('users', $column_order, $column_search, $order, $where);
    $data = array();
    $no = $request->getPost("start");

    foreach ($lists as $list) {
      $no++;
      $row    = array();
      $row['no'] = $no;
      $row['username'] = $list->username;
      $row['email'] = $list->secret;
      $data[] = $row;
    }
    $output = array(
      "draw" => $request->getPost("draw"),
      "recordsTotal" => $list_data->count_all('users', $where),
      "recordsFiltered" => $list_data->count_filtered('users', $column_order, $column_search, $order, $where),
      "data" => $data,
    );

    return json_encode($output);
  }
}

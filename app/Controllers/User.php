<?php

namespace App\Controllers;

use App\Libraries\TwigLib;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User as UserEntity;
use Config\Services;

class User extends BaseController
{
  protected $request;
  protected $model;
  protected $users;

  public function __construct()
  {
    $this->request = Services::request();
    $this->model = new UserModel($this->request);
    $this->users = auth()->getProvider();
  }

  public function index()
  {
    return $this->twig->render('user/index.html.twig');
  }

  public function ajaxList()
  {
    $request = \Config\Services::request();
    $list_data = $this->model;
    $where = ['active =' => 1, 'deleted_at' => null];
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
      $row['id'] = $list->id;
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

  public function create()
  {
    helper('form');

    return $this->twig->render('user/create');
  }

  public function store()
  {
    // Simpan pengguna baru ke database
    $data = $this->request->getPost();

    // Get the User Provider (UserModel by default)

    $user = new UserEntity($data);

    if ($this->users->save($user)) {
      $user = $this->users->findById($this->users->getInsertID());
      $user->activate();

      session()->setFlashdata('success', 'Berhasil Tambah Pengguna');
      return redirect()->to('/user');
    }

    return false;
  }

  public function edit($id)
  {
    // helper('form');

    $user = $this->users->findById($id);

    if (!$user) {
      return redirect()->to('/user')->with('error', 'Pengguna tidak ditemukan.');
    }

    return $this->twig->render('user/edit', ['user' => $user]);
  }

  public function update($id)
  {

    $rules = [
      'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]',
      'password' => 'permit_empty|min_length[8]', // Password opsional, minimal 8 karakter jika diisi
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost();

    //periksa apakah password diisi
    if (empty($data['password'])) {
      unset($data['password']); //hapus password dari data jika kosong
    } else {
      // hash password ketika diisi
      $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
      unset($data['password']);
    }

    //update pengguna
    if ($this->model->update($id, $data)) {
      return redirect()->to('/user')->with('success', 'Pengguna berhasil diperbarui.');
    } else {
      return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }
  }

  public function delete($id)
  {
    // Hapus pengguna berdasarkan ID
    $this->model->delete($id);

    return redirect()->to('/user')->with('success', 'Berhasil Hapus Pengguna');
  }
}

<?php

namespace App\Controllers;

use App\Models\PositionModel;
use Exception;

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

  public function new()
  {
    // Jika request AJAX, kembalikan hanya konten form
    if ($this->request->isAJAX()) {
      return $this->twig->render('position/new');
    }
  }

  // Menampilkan form edit
  public function edit($id)
  {
    $data['item'] = $this->model->find($id);

    if (!$data['item']) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Data tidak ditemukan'
      ]);
    }

    // Jika request AJAX, kembalikan hanya konten form
    if ($this->request->isAJAX()) {
      return $this->twig->render('position/edit', $data);
    }
  }

  // Menyimpan data (add/update)
  public function store()
  {
    $data = $this->request->getPost();

    // Validasi data
    $validation = \Config\Services::validation();
    $validation->setRules([
      'name' => 'required',
    ]);

    if (!$validation->run($data)) {
      return $this->response->setJSON([
        'success' => false,
        'message' => $validation->getErrors()
      ]);
    }

    // Simpan data
    try {
      if (!empty($data['id'])) {
        // Update
        $this->model->update($data['id'], $data);
        $message = 'Data berhasil diupdate';
      } else {
        // Insert
        $this->model->insert($data);
        $message = 'Data berhasil ditambahkan';
      }

      return $this->response->setJSON([
        'success' => true,
        'message' => $message
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Gagal menyimpan data: ' . $e->getMessage()
      ]);
    }
  }

  public function delete($id)
  {
    try {
      $data = $this->model->find($id);

      if ($data) {
        // Hapus pengguna berdasarkan ID
        $this->model->delete($id);

        return $this->response->setJSON([
          'success' => true,
          'message' => "Data berhasil dihapus"
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => "Gagal hapus data"
        ]);
      }
    } catch (Exception $e) {
      var_dump($e);
    }
  }
}

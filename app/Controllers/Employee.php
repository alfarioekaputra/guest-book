<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\PositionModel;
use Exception;

class Employee extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmployeeModel();
    }

    public function index()
    {
        $data = [
            'new' => url_to('Employee::new'),
            'store' => url_to('Employee::store')
        ];

        return $this->twig->render('employee/index', $data);
    }

    public function ajaxList()
    {
        $lists = $this->model->getData();

        return $this->response->setJSON($lists);
    }

    public function getPositions()
    {
        $data = $this->request->getPost();

        $response = [];

        if (!isset($postData['searchTerm'])) {
            // Fetch record
            $positions = new PositionModel();
            $userlist = $positions->select('id,name')
                ->orderBy('name')
                ->findAll(5);
        } else {
            $searchTerm = $postData['searchTerm'];

            // Fetch record
            $positions = new PositionModel();
            $userlist = $positions->select('id,name')
                ->like('name', $searchTerm)
                ->orderBy('name')
                ->findAll(5);
        }

        $data = array();
        foreach ($userlist as $user) {
            $data[] = array(
                "id" => $user['id'],
                "text" => $user['name'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }

    public function new()
    {
        $position = new PositionModel();
        $data['options'] = $position->findAll();

        if ($this->request->isAJAX())
            return $this->twig->render('employee/new', $data);

        return $this->displayError403();
    }

    public function edit($id)
    {
        $position = new PositionModel();
        $data['options'] = $position->findAll();

        $data['item'] = $this->model->find($id);

        if (!$data['item']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($this->request->isAJAX())
            return $this->twig->render('employee/edit', $data);

        return $this->displayError403();
    }

    public function store()
    {
        $data = $this->request->getPost();

        // Validasi data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'position_id' => 'required',
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
                $data['updated_at'] = dateNow();

                $this->model->update($data['id'], $data);
                $message = 'Data berhasil diupdate';
            } else {
                // Insert
                $data['created_at'] = dateNow();

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

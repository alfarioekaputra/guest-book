<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\PositionModel;

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

        // if ($this->request->isAJAX())
        return $this->twig->render('employee/new', $data);

        // return $this->displayError403();
    }

    public function store()
    {
        //
    }
}

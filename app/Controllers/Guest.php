<?php

namespace App\Controllers;

use App\Models\CodeGeneratorModel;
use App\Models\EmployeeModel;
use App\Models\GuestModel;
use App\Models\IdentityModel;
use CodeIgniter\Email\Email;

class Guest extends BaseController
{
    protected $model;
    protected $modelEmployee;
    protected $modelIdentity;
    protected $CodeGeneratorModel;

    public function __construct()
    {
        $this->model = new GuestModel();
        $this->modelEmployee = new EmployeeModel();
        $this->modelIdentity = new IdentityModel();
        $this->CodeGeneratorModel = new CodeGeneratorModel();
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

    public function checkIn()
    {
        $data['employees'] = $this->modelEmployee
            ->select('employees.id, employees.name, positions.name as position')
            ->where('employees.status', 'active')
            ->where('employees.deleted_at', null)
            ->join('positions', 'positions.id = employees.position_id')
            ->findAll();

        $data['identities'] = $this->modelIdentity
            ->where('deleted_at', null)
            ->findAll();

        return $this->twig->render('guest/check-in', $data);
    }

    public function saveCheckIn()
    {
        // Validate form inputs
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]',
            'description' => 'required',
            'photo' => 'required',
            'email' => 'required|valid_email',
            'phone_number' => 'required|min_length[10]',
            'start_time' => 'required',
            'employee_id' => 'required',
            'identity_id' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        // Get form data
        $formData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'email' => $this->request->getPost('email', FILTER_SANITIZE_EMAIL),
            'phone_number' => $this->request->getPost('phone_number'),
            'start_time' => $this->request->getPost('start_time'),
            'employee_id' => $this->request->getPost('employee_id'),
            'identity_id' => $this->request->getPost('identity_id'),
        ];

        // Process photo
        $photo = $this->request->getPost('photo');
        $photo = str_replace('data:image/jpeg;base64,', '', $photo);

        // Create unique filename
        $filename = 'photo_' . time() . '.jpg';

        // Decode and save image
        $decodedImage = base64_decode($photo);
        $filepath = WRITEPATH . 'uploads/' . $filename;
        file_put_contents($filepath, $decodedImage);

        // Combine photo data with form data
        $data = array_merge($formData, [
            'guest_number' => $this->CodeGeneratorModel->generateCode('G', 'guests', 'guest_number'),
            'photo' => 'writable/uploads/' . $filename,
            'created_at' => dateNow()
        ]);

        // Save to database
        $saved = $this->model->insert($data);

        if ($saved) {
            $dataEmail = [
                'namatamu'  => $this->request->getPost('name'),
                'keperluan' => $this->request->getPost('description'),
                'waktu'     => $this->request->getPost('start_time') . ', ' . date('d M Y', strtotime($this->request->getPost('created_at'))),
            ];

            // Load template email dan replace placeholder
            $emailBody = view('email/notification', $dataEmail);

            $employeeMail = $this->modelEmployee
                ->select('employees.email')
                ->where('deleted_at', null)
                ->where('status', 'active')
                ->find($this->request->getPost('employee_id'));

            // Konfigurasi email
            $email = new Email();
            $email->setFrom('noreply@guest-book.test', 'Guest Book');
            $email->setTo($employeeMail['email']);
            $email->setSubject('Pemberitahuan Pertemuan');
            $email->setMessage($emailBody); // Gunakan template yang sudah di-render

            // Kirim email
            if ($email->send()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data saved successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $email->printDebugger()
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to save data'
        ]);
    }

    public function photo(string $name)
    {
        $path = WRITEPATH . 'uploads/';

        if (!file_exists($path . $name)) {
            $name = 'placeholder.jpg';
        }
        return $this->response->download($path . $name, null);
    }
}

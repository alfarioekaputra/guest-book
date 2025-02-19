<?php

namespace App\Models;

use App\Traits\DataTablesTrait;
use CodeIgniter\Model;

class GuestModel extends Model
{
    use DataTablesTrait;

    protected $table            = 'guests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getData()
    {
        $column_select = "guests.id as id_guest, guests.guest_number, guests.phone_number, guests.start_time, guests.end_time, guests.photo, guests.name, guests.email, employees.name as employee, identities.name as identity, guests.status";
        $column_order = ['guests.name'];
        $column_search = ['guests.name', 'guests.guest_number', 'guests.phone_number', 'guests.start_time', 'guests.end_time', 'guests.email', 'employees.name', 'identities.name'];
        $order = ['guests.id' => 'ASC'];
        $where = ['guests.deleted_at' => null];

        // Contoh penggunaan join
        $joins = [
            [
                'table' => 'employees',
                'condition' => 'employees.id = guests.employee_id',
                'type' => 'left'
            ],
            [
                'table' => 'identities',
                'condition' => 'identities.id = guests.identity_id',
                'type' => 'left'
            ]
        ];

        // $joins = [];

        $lists = $this->getDataTables(
            $this->table,
            $column_select,
            $column_order,
            $column_search,
            $order,
            $where, // where condition (optional)
            $joins // joins (optional)
        );

        $data = [];
        foreach ($lists as $key => $item) {

            $row = [];
            $row['no'] = $key + 1;
            $row['id'] = $item->id_guest;
            $row['guest_number'] = $item->guest_number;
            $row['name'] = $item->name;
            $row['email'] = $item->email;
            $row['employee'] = $item->employee;
            $row['identity'] = $item->identity;
            $row['description'] = $item->description;
            $row['status'] = $item->status;
            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->countAll($this->table, '', $joins),
            "recordsFiltered" => $this->countFiltered(
                $this->table,
                $column_select,
                $column_order,
                $column_search,
                $order,
                $where,
                $joins
            ),
            "data" => $data
        ];

        return $output;
    }
}

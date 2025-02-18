<?php

namespace App\Models;

use App\Traits\DataTablesTrait;
use CodeIgniter\Model;

class EmployeeModel extends Model
{
    use DataTablesTrait;

    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $allowedFields    = ['position_id', 'name', 'phone_number', 'address', 'email', 'status', 'created_at', 'updated_at'];
    protected $dateFormat       = 'datetime';

    public function getData()
    {
        $column_select = "employees.id as id_employee, employees.name, employees.email, positions.name as position, employees.status";
        $column_order = ['employees.name'];
        $column_search = ['employees.name'];
        $order = ['employees.id' => 'ASC'];
        $where = ['employees.deleted_at' => null];

        // Contoh penggunaan join
        $joins = [
            [
                'table' => 'positions',
                'condition' => 'positions.id = employees.position_id',
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
            $row['id'] = $item->id_employee;
            $row['name'] = $item->name;
            $row['email'] = $item->email;
            $row['position'] = $item->position;
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

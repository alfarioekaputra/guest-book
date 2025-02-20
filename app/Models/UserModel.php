<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\DataTablesTrait;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
  use DataTablesTrait;

  protected $table = 'users';

  protected function initialize(): void
  {
    parent::initialize();

    $this->allowedFields = [
      ...$this->allowedFields,

      // 'first_name',
    ];
  }

  public function getData()
  {
    $column_select = "*";
    $column_order = ['name'];
    $column_search = ['name'];
    $order = ['id' => 'DESC'];
    $where = ['active =' => 1, 'deleted_at' => null];

    //column_order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    $column_order = array('users.username', 'auth_identities.secret');
    $column_search = array('users.username');
    $order = array('users.username' => 'asc');

    // Contoh penggunaan join
    $joins = [
      [
        'table' => 'auth_identities',
        'condition' => 'auth_identities.user_id = users.id',
        'type' => 'left'
      ]
    ];

    $list = $this->getDataTables(
      $this->table,
      $column_select,
      $column_order,
      $column_search,
      $order,
      $where, // where condition (optional)
      $joins // joins (optional)
    );

    $data = [];
    foreach ($list as $key => $user) {
      $row = [];
      $row['no'] = $key + 1;
      $row['id'] = $user->id;
      $row['username'] = $user->username;
      $row['email'] = $user->secret;
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

<?php

namespace App\Models;

use App\Traits\DataTablesTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class IdentityModel extends Model
{
  use SlugTrait, DataTablesTrait;

  protected $table            = 'identities';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $createdField     = 'created_at';
  protected $updatedField     = 'updated_at';
  protected $deletedField     = 'deleted_at';
  protected $allowedFields    = ['name', 'slug'];
  protected $dateFormat       = 'datetime';

  public function getData()
  {
    $column_order = ['name'];
    $column_search = ['name'];
    $order = ['id' => 'DESC'];
    $where = ['deleted_at' => null];

    // Contoh penggunaan join
    // $joins = [
    //   [
    //     'table' => 'auth_identities',
    //     'condition' => 'auth_identities.user_id = users.id',
    //     'type' => 'left'
    //   ]
    // ];

    $joins = [];

    $list = $this->getDataTables(
      $this->table,
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
      $row['name'] = $user->name;
      $row['slug'] = $user->slug;
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->countAll($this->table, '', $joins),
      "recordsFiltered" => $this->countFiltered(
        $this->table,
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

  public function insert($data = null, bool $returnID = true)
  {
    if (is_array($data) && isset($data['name'])) {
      $data['slug'] = $this->generateSlug($data['name']);
    }

    return parent::insert($data, $returnID);
  }

  // Override method update untuk mengupdate slug jika nama berubah
  public function update($id = null, $data = null): bool
  {
    if (is_array($data) && isset($data['name'])) {
      $data['slug'] = $this->generateSlug($data['name']);
    }
    return parent::update($id, $data);
  }
}

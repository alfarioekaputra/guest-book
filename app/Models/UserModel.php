<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
  public $builder;

  protected function initialize(): void
  {
    parent::initialize();

    $this->allowedFields = [
      ...$this->allowedFields,

      // 'first_name',
    ];
  }

  protected function _get_datatables_query($table, $column_order, $column_search, $order)
  {
    $this->builder = $this->db->table($table);
    //jika ingin join formatnya adalah sebagai berikut :
    $this->builder->join('auth_identities', 'auth_identities.user_id = users.id', 'left');
    //end Join
    $i = 0;

    foreach ($column_search as $item) {
      if ($_POST['search']['value']) {

        if ($i === 0) {
          $this->builder->groupStart();
          $this->builder->like($item, $_POST['search']['value']);
        } else {
          $this->builder->orLike($item, $_POST['search']['value']);
        }

        if (count($column_search) - 1 == $i)
          $this->builder->groupEnd();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($order)) {
      $order = $order;
      $this->builder->orderBy(key($order), $order[key($order)]);
    }
  }

  public function get_datatables($table, $column_order, $column_search, $order, $data = '')
  {
    $this->_get_datatables_query($table, $column_order, $column_search, $order);
    if ($_POST['length'] != -1)
      $this->builder->limit((int)$_POST['length'], (int)$_POST['start']);
    if ($data) {
      $this->builder->where($data);
    }

    $query = $this->builder->get();
    return $query->getResult();
  }

  public function count_filtered($table, $column_order, $column_search, $order, $data = '')
  {
    $this->_get_datatables_query($table, $column_order, $column_search, $order);
    if ($data) {
      $this->builder->where($data);
    }
    $this->builder->get();
    return $this->builder->countAll();
  }

  public function count_all($table, $data = '')
  {
    if ($data) {
      $this->builder->where($data);
    }
    $this->builder->from($table);

    return $this->builder->countAll();
  }
}

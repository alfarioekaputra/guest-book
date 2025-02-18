<?php

namespace App\Traits;

trait DataTablesTrait
{
  protected $builder;

  protected function getDataTablesQuery($table, $column_select, $column_order, $column_search, $order, $joins = [])
  {
    $this->builder = $this->db->table($table);
    $this->builder->select($column_select);
    // Handle joins if provided
    if (!empty($joins)) {
      foreach ($joins as $join) {
        $this->builder->join(
          $join['table'],
          $join['condition'],
          $join['type'] ?? 'left'
        );
      }
    }

    // Handle search
    $i = 0;
    foreach ($column_search as $item) {
      if (isset($_POST['search']['value'])) {
        $searchValue = $_POST['search']['value'];

        if ($i === 0) {
          $this->builder->groupStart();
          $this->builder->like($item, $searchValue);
        } else {
          $this->builder->orLike($item, $searchValue);
        }

        if (count($column_search) - 1 == $i) {
          $this->builder->groupEnd();
        }
      }
      $i++;
    }


    // Handle ordering
    // sementara disable order by kolom terlebih dahulu
    // if (isset($_POST['order'])) {
    //   $this->builder->orderBy(
    //     $column_order[$_POST['order']['0']['name']],
    //     $_POST['order']['0']['dir']
    //   );
    // } else

    if (isset($order)) {
      $this->builder->orderBy(key($order), $order[key($order)]);
    }
  }

  public function getDataTables($table, $column_select, $column_order, $column_search, $order, $data = '', $joins = [])
  {
    $this->getDataTablesQuery($table, $column_select, $column_order, $column_search, $order, $joins);

    // Handle pagination
    if (isset($_POST['length']) && $_POST['length'] != -1) {
      $this->builder->limit(
        (int)$_POST['length'],
        (int)$_POST['start']
      );
    }

    // Handle additional where conditions
    if (!empty($data)) {
      $this->builder->where($data);
    }

    return $this->builder->get()->getResult();
  }

  public function countFiltered($table, $column_select, $column_order, $column_search, $order, $data = '', $joins = [])
  {
    $this->getDataTablesQuery($table, $column_select, $column_order, $column_search, $order, $joins);

    if (!empty($data)) {
      $this->builder->where($data);
    }

    return $this->builder->countAllResults();
  }

  public function countAll($table, $data = '', $joins = [])
  {
    $this->builder = $this->db->table($table);

    // Handle joins if provided
    if (!empty($joins)) {
      foreach ($joins as $join) {
        $this->builder->join(
          $join['table'],
          $join['condition'],
          $join['type'] ?? 'left'
        );
      }
    }

    if (!empty($data)) {
      $this->builder->where($data);
    }

    return $this->builder->countAllResults();
  }
}

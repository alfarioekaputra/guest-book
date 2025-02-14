<?php

namespace App\Models;

use App\Traits\SlugTrait;
use CodeIgniter\Model;

class ReferenceDetailModel extends Model
{
  use SlugTrait;

  protected $table            = 'reference_detail';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [];

  protected $allowEmptyInserts = false;
  protected $updateOnlyChanged = true;

  protected $casts = [];
  protected $castHandlers = [];

  // Dates
  protected $useTimestamps = false;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  // Validation
  protected $validationRules      = [];
  protected $validationMessages   = [];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;

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

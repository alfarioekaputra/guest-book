<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PositionSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        "name" => "MANAGER",
        "slug" => "manager"
      ],
      [
        "name" => "SUPERVISOR",
        "slug" => "supervisor"
      ],
      [
        "name" => "OPERATOR",
        "slug" => "operator"
      ],
      [
        "name" => "SECURITY",
        "slug" => "security"
      ]
    ];

    $this->db->table('positions')->insertBatch($data);
  }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IdentitySeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        "name" => "Kartu Pegawai",
        "slug" => "kartu-pegawai"
      ],
      [
        "name" => "SIM",
        "slug" => "sim"
      ],
      [
        "name" => "KTP",
        "slug" => "ktp"
      ],
      [
        "name" => "PASSPORT",
        "slug" => "passport"
      ]
    ];

    $this->db->table('identities')->insertBatch($data);
  }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferenceTable extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type' => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
      ],
      'name' => [
        'type' => 'VARCHAR',
        'constraint' => '100'
      ],
      'slug' => [
        'type' => 'VARCHAR',
        'constraint' => '255'
      ]
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('m_reference');
  }

  public function down()
  {
    $this->forge->dropTable('m_reference');
  }
}

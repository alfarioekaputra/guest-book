<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferenceDetailTable extends Migration
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
      'reference_id' => [
        'type' => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
      ],
      'name' => [
        'type' => 'VARCHAR',
        'constraint' => '100'
      ],
      'extrafield_1' => [
        'type' => 'VARCHAR',
        'constraint' => '255'
      ],
      'extrafield_2' => [
        'type' => 'VARCHAR',
        'constraint' => '255'
      ],
      'extrafield_2' => [
        'type' => 'VARCHAR',
        'constraint' => '255'
      ],
      'order' => [
        'type' => 'TINYINT',
        'constraint' => '1'
      ],
      'status' => [
        'type' => 'TINYINT',
        'constraint' => '1'
      ],
      'slug' => [
        'type' => 'VARCHAR',
        'constraint' => '255'
      ]
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('reference_id', 'm_reference', 'id', '', 'CASCADE');
    $this->forge->createTable('m_reference_detail');
  }

  public function down()
  {
    $this->forge->dropTable('m_reference_detail');
  }
}

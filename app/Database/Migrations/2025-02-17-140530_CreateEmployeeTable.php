<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeTable extends Migration
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
            'position_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true
            ],
            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('position_id', 'positions', 'id');
        $this->forge->createTable('employees');
    }

    public function down()
    {
        $this->forge->dropTable('employees');
    }
}

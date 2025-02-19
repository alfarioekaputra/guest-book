<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuestTable extends Migration
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
            'employee_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
            ],
            'identity_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'guest_number' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'start_time' => [
                'type' => 'TIME',
            ],
            'end_time' => [
                'type' => 'TIME',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['proses', 'selesai'],
                'default'    => 'proses',
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
        $this->forge->addForeignKey('employee_id', 'employees', 'id');
        $this->forge->addForeignKey('identity_id', 'identities', 'id');
        $this->forge->createTable('guests');
    }

    public function down()
    {
        $this->forge->dropTable('guests');
    }
}

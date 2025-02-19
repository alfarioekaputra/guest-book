<?php
namespace App\Models;

use CodeIgniter\Model;

class CodeGeneratorModel extends Model
{
    public function generateCode(string $prefix = 'G', string $table = '', string $field = ''): string
    {
        $prefix = substr($prefix, 0, 1);
        $year = date('y');
        $month = date('m');

        $todayPrefix = $prefix . $year . $month;

        $lastRecord = $this->db->table($table)
            ->like($field, $todayPrefix, 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        
        if ($lastRecord) {
            $sequence = (int)substr($lastRecord->$field, -4);
            $sequence++;
        } else {
            $sequence = 1;
        }

        //reset if exceeds 9999
        if ($sequence > 9999) {
            $sequence = 1;
        }

        return $todayPrefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
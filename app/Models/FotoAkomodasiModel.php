<?php

namespace App\Models;

use CodeIgniter\Model;

class FotoAkomodasiModel extends Model
{
    protected $table = 'foto_akomodasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['akomodasi_id', 'foto', 'urutan'];
    
    public function getFotoByAkomodasi($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}
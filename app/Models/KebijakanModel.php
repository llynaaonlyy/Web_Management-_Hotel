<?php

namespace App\Models;

use CodeIgniter\Model;

class KebijakanModel extends Model
{
    protected $table = 'kebijakan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['akomodasi_id', 'check_in', 'check_out', 'kebijakan_pembatalan', 'aturan_lainnya'];
    
    public function getKebijakanByAkomodasi($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)->first();
    }
}
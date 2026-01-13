<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table = 'fasilitas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['akomodasi_id', 'nama_fasilitas', 'icon'];
    
    public function getFasilitasByAkomodasi($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)->findAll();
    }
}
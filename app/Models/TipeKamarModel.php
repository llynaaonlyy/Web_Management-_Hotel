<?php

namespace App\Models;

use CodeIgniter\Model;

class TipeKamarModel extends Model
{
    protected $table = 'tipe_kamar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['akomodasi_id', 'nama_tipe', 'harga_per_malam', 'kapasitas', 'ukuran_kamar', 'fasilitas_kamar', 'foto', 'stok_kamar', 'status'];
    
    public function getTipeKamarByAkomodasi($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)->findAll();
    }
    
    public function getTipeKamarTersedia($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)
                    ->where('stok_kamar >', 0)
                    ->findAll();
    }
}
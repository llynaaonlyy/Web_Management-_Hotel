<?php

namespace App\Models;

use CodeIgniter\Model;

class AkomodasiModel extends Model
{
    protected $table = 'akomodasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'tipe', 'deskripsi', 'alamat', 'kota', 'rating', 'foto_utama'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getDetailAkomodasi($id)
    {
        return $this->find($id);
    }
    
    public function getByTipe($tipe)
    {
        if ($tipe == 'semua' || empty($tipe)) {
            return $this->findAll();
        }
        return $this->where('tipe', $tipe)->findAll();
    }
    
    public function searchAkomodasi($keyword)
{
    return $this->like('nama', $keyword)
                ->orLike('kota', $keyword)
                ->orLike('deskripsi', $keyword)
                ->findAll();
}
}

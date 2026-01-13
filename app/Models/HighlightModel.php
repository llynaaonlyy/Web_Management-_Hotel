<?php

namespace App\Models;

use CodeIgniter\Model;

class HighlightModel extends Model
{
    protected $table = 'highlights';
    protected $primaryKey = 'id';
    protected $allowedFields = ['akomodasi_id', 'highlight'];
    
    public function getHighlightsByAkomodasi($akomodasiId)
    {
        return $this->where('akomodasi_id', $akomodasiId)->findAll();
    }
}
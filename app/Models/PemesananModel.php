<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'akomodasi_id', 'tipe_kamar_id', 'tanggal_checkin', 'tanggal_checkout', 'jumlah_malam', 'biaya_admin_ppn', 'total_harga', 'metode_pembayaran', 'bukti_pembayaran', 'status', 'catatan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getDetailPemesanan($id)
    {
        return $this->select('pemesanan.*, akomodasi.nama as nama_akomodasi, akomodasi.alamat, tipe_kamar.nama_tipe')
                    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
                    ->find($id);
    }
    
    public function getPemesananByUser($userId)
    {
        return $this->select('pemesanan.*, akomodasi.nama as nama_akomodasi, tipe_kamar.nama_tipe')
                    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
                    ->where('pemesanan.user_id', $userId)
                    ->orderBy('pemesanan.created_at', 'DESC')
                    ->findAll();
    }
    
   public function getAllPemesananWithDetails()
    {
        return $this->select('pemesanan.*, 
                            akomodasi.nama as nama_akomodasi, 
                            tipe_kamar.nama_tipe, 
                            users.nama as nama_tamu, 
                            users.email as email_tamu,
                            tipe_kamar.harga_per_malam,')
                    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
                    ->join('users', 'users.id = pemesanan.user_id')
                    ->orderBy('pemesanan.created_at', 'DESC')
                    ->findAll();
    }
    
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
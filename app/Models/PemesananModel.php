<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'akomodasi_id', 'tipe_kamar_id', 'tanggal_checkin', 'tanggal_checkout', 'jumlah_malam', 'biaya_admin_ppn', 'total_harga', 'metode_pembayaran', 'bukti_pembayaran', 'status', 'catatan', 'catatan_internal', 'status_lama'];
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
   public function getHistoriByUser($userId)
{
    return $this->select('
        pemesanan.id,
        pemesanan.tanggal_checkin,
        pemesanan.tanggal_checkout,
        pemesanan.jumlah_malam,
        pemesanan.total_harga,
        pemesanan.metode_pembayaran,
        pemesanan.bukti_pembayaran,
        pemesanan.created_at,

        akomodasi.nama as nama_akomodasi,
        akomodasi.kota,
        akomodasi.foto_utama,
        tipe_kamar.nama_tipe,
        tipe_kamar.harga_per_malam,

        COALESCE(psl.status_baru, pemesanan.status) AS status_terkini,
        psl.keterangan AS catatan_petugas
    ')
    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')

    // JOIN ke status log TERAKHIR
    ->join(
        '(SELECT pemesanan_id, status_baru, keterangan
          FROM pemesanan_status_log
          WHERE id IN (
              SELECT MAX(id) FROM pemesanan_status_log GROUP BY pemesanan_id
          )
        ) psl',
        'psl.pemesanan_id = pemesanan.id',
        'left'
    )

    ->where('pemesanan.user_id', $userId)
    ->orderBy('pemesanan.created_at', 'DESC')
    ->findAll();
}

    
    public function getDetailPemesananFull($id)
    {
        return $this->select('pemesanan.*, 
                             akomodasi.nama as nama_akomodasi, 
                             akomodasi.alamat, 
                             akomodasi.kota,
                             tipe_kamar.nama_tipe,
                             tipe_kamar.harga_per_malam,
                             users.nama as nama_tamu,
                             users.email as email_tamu,
                             users.no_telp as no_telp_tamu')
                    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
                    ->join('users', 'users.id = pemesanan.user_id')
                    ->find($id);
    }
    
    public function getAllPemesananWithDetails()
    {
        return $this->select('pemesanan.*, 
                             akomodasi.nama as nama_akomodasi, 
                             tipe_kamar.nama_tipe, 
                             users.nama as nama_tamu,
                             users.email as email_tamu,
                             users.no_telp as no_telp_tamu')
                    ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
                    ->join('users', 'users.id = pemesanan.user_id')
                    ->orderBy('pemesanan.created_at', 'DESC')
                    ->findAll();
    }
    
    
    public function getAllPemesanan()
    {
        return $this->select('pemesanan.*, akomodasi.nama as nama_akomodasi, tipe_kamar.nama_tipe, users.nama as nama_user')
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
<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\UserModel;
use App\Models\AkomodasiModel;
use App\Models\TipeKamarModel;

class Staff extends BaseController
{
    protected $pemesananModel;
    protected $userModel;
    protected $akomodasiModel;
    protected $tipeKamarModel;
    protected $session;
    protected $email;
    
    public function __construct()
    {
        $this->pemesananModel = new PemesananModel();
        $this->userModel = new UserModel();
        $this->akomodasiModel = new AkomodasiModel();
        $this->tipeKamarModel = new TipeKamarModel();
        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
    }
    
    public function dashboard()
    {
        // Statistik harian
        $today = date('Y-m-d');
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'pemesanan' => $this->pemesananModel->getAllPemesananWithDetails(),
            'stats' => [
                'pemesanan_hari_ini' => $this->pemesananModel->where('DATE(created_at)', $today)->countAllResults(),
                'checkin_hari_ini' => $this->pemesananModel->where('tanggal_checkin', $today)->where('status', 'check-in')->countAllResults(),
                'kamar_terpakai' => $this->pemesananModel->where('status', 'check-in')->countAllResults(),
                'pending' => $this->pemesananModel->where('status', 'pending')->countAllResults()
            ]
        ];
        
        return view('/staff/dashboard', $data);
    }
    
    // Controller Staff.php
     public function daftarSemuaPemesanan()
    {
        // Panggil method dari Model yang ada
        $pemesanan = $this->pemesananModel->getAllPemesananWithDetails();

        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'pemesanan' => $pemesanan
        ];

        return view('staff/dashboard', $data);
    }

    public function detailPemesanan($id)
        {
            $pemesanan = $this->pemesananModel->getDetailPemesananFull($id);

            if (!$pemesanan) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            $tamu = $this->userModel->find($pemesanan['user_id']);

            $db = \Config\Database::connect();
            $statusLog = $db->table('pemesanan_status_log')
                ->select('pemesanan_status_log.*, users.nama as changed_by_name')
                ->join('users', 'users.id = pemesanan_status_log.changed_by', 'left')
                ->where('pemesanan_id', $id)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            $data = [
                'user' => [
                    'nama' => $this->session->get('nama'),
                    'role' => $this->session->get('role')
                ],
                'pemesanan' => $pemesanan,
                'tamu' => $tamu,
                'status_log' => $statusLog
            ];

            return view('staff/detail_pemesanan', $data);
        }
    
    public function updateStatus()
    {
        $pemesananId = $this->request->getPost('pemesanan_id');
        $statusBaru = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');
        
        // Get pemesanan lama
        $pemesanan = $this->pemesananModel->find($pemesananId);
        if (!$pemesanan) {
            return redirect()->back()->with('error', 'Pemesanan tidak ditemukan');
        }
        
        $statusLama = $pemesanan['status'];
        
        // Update status
        $this->pemesananModel->update($pemesananId, ['status' => $statusBaru]);
        
        // Log perubahan status
        $db = \Config\Database::connect();
        $db->table('pemesanan_status_log')->insert([
            'pemesanan_id' => $pemesananId,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'changed_by' => $this->session->get('user_id'),
            'keterangan' => $keterangan
        ]);
        
        // Kirim email jika status menjadi confirmed
        if ($statusBaru == 'confirmed' && $statusLama != 'confirmed') {
            $this->sendConfirmationEmail($pemesananId);
        }
        
        return redirect()->to('/staff/pemesanan/' . $pemesananId)
                        ->with('success', 'Status pemesanan berhasil diubah menjadi ' . $statusBaru);
    }
    
    public function updateCatatanInternal()
    {
        $pemesananId = $this->request->getPost('pemesanan_id');
        $catatanInternal = $this->request->getPost('catatan_internal');
        
        $this->pemesananModel->update($pemesananId, [
            'catatan_internal' => $catatanInternal
        ]);
        
        return redirect()->back()->with('success', 'Catatan internal berhasil diupdate');
    }
    
    private function sendConfirmationEmail($pemesananId)
    {
        $pemesanan = $this->pemesananModel->getDetailPemesananFull($pemesananId);
        $tamu = $this->userModel->find($pemesanan['user_id']);
        
        if (!$tamu || !$tamu['email']) {
            return false;
        }
        
        // Konfigurasi email
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => 'smtp.gmail.com', // Sesuaikan dengan SMTP server Anda
            'SMTPPort' => 587,
            'SMTPUser' => 'your-email@gmail.com', // Ganti dengan email Anda
            'SMTPPass' => 'your-app-password', // Ganti dengan app password
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];
        
        $this->email->initialize($config);
        
        // Set email
        $this->email->setFrom('noreply@hotelku.com', 'Hotelku');
        $this->email->setTo($tamu['email']);
        $this->email->setSubject('Konfirmasi Pemesanan - Hotelku');
        
        // Email body
        $message = view('emails/booking_confirmation', [
            'tamu' => $tamu,
            'pemesanan' => $pemesanan
        ]);
        
        $this->email->setMessage($message);
        
        // Kirim email
        if ($this->email->send()) {
            // Update flag email_sent
            $this->pemesananModel->update($pemesananId, ['email_sent' => true]);
            return true;
        } else {
            log_message('error', 'Failed to send email: ' . $this->email->printDebugger());
            return false;
        }
    }
    
    public function kelolaKamar()
    {
        $keyword = $this->request->getGet('search');
        
        $builder = $this->tipeKamarModel;
        
        if ($keyword) {
            $builder = $builder->like('nama_tipe', $keyword);
        }
        
        $tipeKamar = $builder->select('tipe_kamar.*, akomodasi.nama as nama_akomodasi')
                             ->join('akomodasi', 'akomodasi.id = tipe_kamar.akomodasi_id')
                             ->findAll();
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'tipe_kamar' => $tipeKamar
        ];
        
        return view('staff/kelola_kamar', $data);
    }
    
    public function updateStokKamar()
    {
        $kamarId = $this->request->getPost('kamar_id');
        $stok = $this->request->getPost('stok_kamar');
        
        $this->tipeKamarModel->update($kamarId, ['stok_kamar' => $stok]);
        
        return redirect()->back()->with('success', 'Stok kamar berhasil diupdate');
    }
    
    public function updateStatusKamar()
    {
        $kamarId = $this->request->getPost('kamar_id');
        $status = $this->request->getPost('status');
        
        $this->tipeKamarModel->update($kamarId, ['status' => $status]);
        
        $statusText = $status == 'available' ? 'tersedia' : 'maintenance';
        return redirect()->back()->with('success', 'Status kamar berhasil diubah menjadi ' . $statusText);
    }
    
    public function dataTamu()
    {
        $keyword = $this->request->getGet('search');
        
        $builder = $this->userModel;
        
        if ($keyword) {
            $builder = $builder->like('nama', $keyword)
                               ->orLike('email', $keyword)
                               ->orLike('no_telp', $keyword);
        }
        
        $tamu = $builder->where('role', 'pelanggan')->findAll();
        
        // Get pemesanan count for each tamu
        foreach ($tamu as &$t) {
            $t['total_pemesanan'] = $this->pemesananModel->where('user_id', $t['id'])->countAllResults();
        }
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'tamu' => $tamu
        ];
        
        return view('staff/data_tamu', $data);
    }
    
    public function detailTamu($id)
    {
        $tamu = $this->userModel->find($id);
        
        if (!$tamu || $tamu['role'] != 'pelanggan') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $pemesanan = $this->pemesananModel->getPemesananByUser($id);
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'tamu' => $tamu,
            'pemesanan' => $pemesanan
        ];
        
        return view('staff/detail_tamu', $data);
    }

        public function profilStaff()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        return view('/staff/profil_staff', ['user' => $user]);
    }
}
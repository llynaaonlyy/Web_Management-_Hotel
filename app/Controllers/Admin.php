<?php

namespace App\Controllers;


use TCPDF;
use App\Models\UserModel;
use App\Models\AkomodasiModel;
use App\Models\FotoAkomodasiModel;
use App\Models\FasilitasModel;
use App\Models\HighlightModel;
use App\Models\KebijakanModel;
use App\Models\TipeKamarModel;
use App\Models\PemesananModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $akomodasiModel;
    protected $fotoModel;
    protected $fasilitasModel;
    protected $highlightModel;
    protected $kebijakanModel;
    protected $tipeKamarModel;
    protected $pemesananModel;
    protected $session;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->akomodasiModel = new AkomodasiModel();
        $this->fotoModel = new FotoAkomodasiModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->highlightModel = new HighlightModel();
        $this->kebijakanModel = new KebijakanModel();
        $this->tipeKamarModel = new TipeKamarModel();
        $this->pemesananModel = new PemesananModel();
        $this->session = \Config\Services::session();
        
        // Middleware: Hanya admin yang bisa akses
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'admin') {
            redirect()->to('/login')->send();
            exit;
        }
    }
    
    // DASHBOARD
    public function dashboard()
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'stats' => [
                'total_akomodasi' => $this->akomodasiModel->countAll(),
                'total_pemesanan' => $this->pemesananModel->countAll(),
                'total_users' => $this->userModel->where('role', 'pelanggan')->countAllResults(),
                'total_pegawai' => $this->userModel->where('role', 'pegawai')->countAllResults()
            ]
        ];
        
        return view('admin/dashboard', $data);
    }
    
    // AKOMODASI
    public function akomodasi()
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'akomodasi' => $this->akomodasiModel->findAll()
        ];
        
        return view('admin/akomodasi', $data);
    }
    
    public function akomodasiForm($id = null)
    {
        $akomodasi = $id ? $this->akomodasiModel->find($id) : null;
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'akomodasi' => $akomodasi,
            'foto' => $id ? $this->fotoModel->where('akomodasi_id', $id)->findAll() : []
        ];
        
        return view('admin/akomodasi_form', $data);
    }
    
    public function akomodasiSave()
    {
        $id = $this->request->getPost('id');
        
        $rules = [
            'nama' => 'required|min_length[3]',
            'tipe' => 'required|in_list[hotel,villa,apart]',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'rating' => 'required|decimal'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'tipe' => $this->request->getPost('tipe'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'rating' => $this->request->getPost('rating')
        ];
        
        // Handle foto utama upload
        $fotoUtama = $this->request->getFile('foto_utama');

        if ($fotoUtama && $fotoUtama->isValid() && !$fotoUtama->hasMoved()) {
            $newName = $fotoUtama->getRandomName();
            $fotoUtama->move(ROOTPATH . 'public/uploads/akomodasi', $newName);
            $data['foto_utama'] = $newName;
        }

        if ($id) {
            // Update
            $this->akomodasiModel->update($id, $data);
            $message = 'Akomodasi berhasil diupdate';
            $akomodasiId = $id;
        } else {
            // Insert
            $akomodasiId = $this->akomodasiModel->insert($data);
            $message = 'Akomodasi berhasil ditambahkan';
        }

        // Handle multiple foto detail
        $fotoDetail = $this->request->getFileMultiple('foto_detail');

        if (!empty($fotoDetail)) {
            $urutan = $this->fotoModel
                ->where('akomodasi_id', $akomodasiId)
                ->countAllResults() + 1;

            foreach ($fotoDetail as $foto) {
                if ($foto->isValid() && !$foto->hasMoved()) {
                    $newName = $foto->getRandomName();
                    $foto->move(ROOTPATH . 'public/uploads/akomodasi', $newName);

                    $this->fotoModel->insert([
                        'akomodasi_id' => $akomodasiId,
                        'foto'         => $newName,
                        'urutan'       => $urutan++
                    ]);
                }
            }
        }

        return redirect()->to('/admin/akomodasi')->with('success', $message);
    }
    
    public function akomodasiDelete($id)
    {
        // Hapus foto-foto terkait
        $fotos = $this->fotoModel->where('akomodasi_id', $id)->findAll();
        foreach ($fotos as $foto) {
            @unlink(WRITEPATH . '../public/uploads/akomodasi/' . $foto['foto']);
        }
        
        $this->akomodasiModel->delete($id);
        
        return redirect()->to('/admin/akomodasi')->with('success', 'Akomodasi berhasil dihapus');
    }
    
    public function fotoDelete($id)
    {
        $foto = $this->fotoModel->find($id);
        if ($foto) {
            @unlink(WRITEPATH . '../public/uploads/akomodasi/' . $foto['foto']);
            $this->fotoModel->delete($id);
        }
        
        return redirect()->back()->with('success', 'Foto berhasil dihapus');
    }
    
    // FASILITAS
    public function fasilitas($akomodasiId)
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'akomodasi' => $this->akomodasiModel->find($akomodasiId),
            'fasilitas' => $this->fasilitasModel->where('akomodasi_id', $akomodasiId)->findAll()
        ];
        
        return view('admin/fasilitas', $data);
    }
    
    public function fasilitasSave()
    {
        $akomodasiId = $this->request->getPost('akomodasi_id');
        $id = $this->request->getPost('id');
        
        $data = [
            'akomodasi_id' => $akomodasiId,
            'nama_fasilitas' => $this->request->getPost('nama_fasilitas'),
            'icon' => $this->request->getPost('icon')
        ];
        
        if ($id) {
            $this->fasilitasModel->update($id, $data);
            $message = 'Fasilitas berhasil diupdate';
        } else {
            $this->fasilitasModel->insert($data);
            $message = 'Fasilitas berhasil ditambahkan';
        }
        
        return redirect()->to('/admin/fasilitas/' . $akomodasiId)->with('success', $message);
    }
    
    public function fasilitasDelete($id)
    {
        $fasilitas = $this->fasilitasModel->find($id);
        $akomodasiId = $fasilitas['akomodasi_id'];
        
        $this->fasilitasModel->delete($id);
        
        return redirect()->to('/admin/fasilitas/' . $akomodasiId)->with('success', 'Fasilitas berhasil dihapus');
    }
    
    // HIGHLIGHTS
    public function highlights($akomodasiId)
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'akomodasi' => $this->akomodasiModel->find($akomodasiId),
            'highlights' => $this->highlightModel->where('akomodasi_id', $akomodasiId)->findAll()
        ];
        
        return view('admin/highlights', $data);
    }
    
    public function highlightsSave()
    {
        $akomodasiId = $this->request->getPost('akomodasi_id');
        $id = $this->request->getPost('id');
        
        $data = [
            'akomodasi_id' => $akomodasiId,
            'highlight' => $this->request->getPost('highlight')
        ];
        
        if ($id) {
            $this->highlightModel->update($id, $data);
            $message = 'Highlight berhasil diupdate';
        } else {
            $this->highlightModel->insert($data);
            $message = 'Highlight berhasil ditambahkan';
        }
        
        return redirect()->to('/admin/highlights/' . $akomodasiId)->with('success', $message);
    }
    
    public function highlightsDelete($id)
    {
        $highlight = $this->highlightModel->find($id);
        $akomodasiId = $highlight['akomodasi_id'];
        
        $this->highlightModel->delete($id);
        
        return redirect()->to('/admin/highlights/' . $akomodasiId)->with('success', 'Highlight berhasil dihapus');
    }
    
    // KEBIJAKAN
    public function kebijakan($akomodasiId)
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'akomodasi' => $this->akomodasiModel->find($akomodasiId),
            'kebijakan' => $this->kebijakanModel->where('akomodasi_id', $akomodasiId)->first()
        ];
        
        return view('admin/kebijakan', $data);
    }
    
    public function kebijakanSave()
    {
        $akomodasiId = $this->request->getPost('akomodasi_id');
        $id = $this->request->getPost('id');
        
        $data = [
            'akomodasi_id' => $akomodasiId,
            'check_in' => $this->request->getPost('check_in'),
            'check_out' => $this->request->getPost('check_out'),
            'kebijakan_pembatalan' => $this->request->getPost('kebijakan_pembatalan'),
            'aturan_lainnya' => $this->request->getPost('aturan_lainnya')
        ];
        
        if ($id) {
            $this->kebijakanModel->update($id, $data);
            $message = 'Kebijakan berhasil diupdate';
        } else {
            $this->kebijakanModel->insert($data);
            $message = 'Kebijakan berhasil ditambahkan';
        }
        
        return redirect()->to('/admin/kebijakan/' . $akomodasiId)->with('success', $message);
    }
    
    // TIPE KAMAR
    public function tipeKamar($akomodasiId = null)
    {
        $builder = $this->tipeKamarModel->select('tipe_kamar.*, akomodasi.nama as nama_akomodasi')
                                        ->join('akomodasi', 'akomodasi.id = tipe_kamar.akomodasi_id');
        
        if ($akomodasiId) {
            $builder->where('akomodasi_id', $akomodasiId);
        }
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'tipe_kamar' => $builder->findAll(),
            'akomodasi' => $akomodasiId ? $this->akomodasiModel->find($akomodasiId) : null
        ];
        
        return view('admin/tipe_kamar', $data);
    }
    
    public function tipeKamarForm($id = null)
    {
        $tipeKamar = $id ? $this->tipeKamarModel->find($id) : null;
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'tipe_kamar' => $tipeKamar,
            'akomodasi' => $this->akomodasiModel->findAll()
        ];
        
        return view('admin/tipe_kamar_form', $data);
    }
    
    public function tipeKamarSave()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'akomodasi_id' => $this->request->getPost('akomodasi_id'),
            'nama_tipe' => $this->request->getPost('nama_tipe'),
            'harga_per_malam' => $this->request->getPost('harga_per_malam'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'ukuran_kamar' => $this->request->getPost('ukuran_kamar'),
            'fasilitas_kamar' => $this->request->getPost('fasilitas_kamar'),
            'stok_kamar' => $this->request->getPost('stok_kamar'),
            'status' => $this->request->getPost('status') ?? 'available'
        ];
        
        // Handle foto upload
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid()) {
            $newName = $foto->getRandomName();
            $foto->move(WRITEPATH . '../public/uploads/kamar', $newName);
            $data['foto'] = $newName;
        }
        
        if ($id) {
            $this->tipeKamarModel->update($id, $data);
            $message = 'Tipe kamar berhasil diupdate';
        } else {
            $this->tipeKamarModel->insert($data);
            $message = 'Tipe kamar berhasil ditambahkan';
        }
        
        return redirect()->to('/admin/tipe-kamar')->with('success', $message);
    }
    
    public function tipeKamarDelete($id)
    {
        $tipeKamar = $this->tipeKamarModel->find($id);
        if ($tipeKamar && $tipeKamar['foto']) {
            @unlink(WRITEPATH . '../public/uploads/kamar/' . $tipeKamar['foto']);
        }
        
        $this->tipeKamarModel->delete($id);
        
        return redirect()->to('/admin/tipe-kamar')->with('success', 'Tipe kamar berhasil dihapus');
    }
    
    // BOOKING (READ ONLY)
    public function booking()
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'booking' => $this->pemesananModel->getAllPemesananWithDetails()
        ];
        
        return view('admin/booking', $data);
    }
    
    // MANAJEMEN USER
    public function users()
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ],
            'users' => $this->userModel->findAll()
        ];
        
        return view('admin/users', $data);
    }
    
    public function userUpdate()
    {
        $id = $this->request->getPost('id');
        $user = $this->userModel->find($id);
        
        // Hanya admin dan pegawai yang bisa diedit
        if ($user['role'] === 'pelanggan') {
            return redirect()->back()->with('error', 'Pelanggan tidak dapat diedit');
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp')
        ];
        
        // Update password jika diisi
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($id, $data);
        
        return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate');
    }
    
    // LAPORAN
    public function laporan()
    {
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ]
        ];
        
        return view('admin/laporan', $data);
    }
    
    public function laporanGenerate()
    {
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = $this->request->getPost('tanggal_selesai');
        
        // Get data pemesanan berdasarkan rentang tanggal
        $pemesanan = $this->pemesananModel
            ->select('pemesanan.*, akomodasi.nama as nama_akomodasi, tipe_kamar.nama_tipe, users.nama as nama_tamu')
            ->join('akomodasi', 'akomodasi.id = pemesanan.akomodasi_id')
            ->join('tipe_kamar', 'tipe_kamar.id = pemesanan.tipe_kamar_id')
            ->join('users', 'users.id = pemesanan.user_id')
            ->where('pemesanan.tanggal_checkin >=', $tanggalMulai)
            ->where('pemesanan.tanggal_checkin <=', $tanggalSelesai)
            ->orderBy('pemesanan.tanggal_checkin', 'ASC')
            ->findAll();
        
        $totalTransaksi = array_sum(array_column($pemesanan, 'total_harga'));
        
        $data = [
            'pemesanan' => $pemesanan,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'total_transaksi' => $totalTransaksi,
            'pembuat_laporan' => $this->session->get('nama'),
            'tanggal_cetak' => date('d F Y')
        ];
        
        // Load library TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Hotelku');
        $pdf->SetAuthor($this->session->get('nama'));
        $pdf->SetTitle('Laporan Pemesanan Hotelku');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Generate HTML content
        $html = view('admin/laporan_pdf', $data);
        
        // Print HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Output PDF
        $pdf->Output('Laporan_Pemesanan_' . date('YmdHis') . '.pdf', 'D');
    }
}
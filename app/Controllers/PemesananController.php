<?php

namespace App\Controllers;

class PemesananController extends BaseController
{
    protected $pemesananModel;
    protected $akomodasiModel;
    protected $tipeKamarModel;
    protected $session;
    
    public function __construct()
    {
        $this->pemesananModel = new \App\Models\PemesananModel();
        $this->akomodasiModel = new \App\Models\AkomodasiModel();
        $this->tipeKamarModel = new \App\Models\TipeKamarModel();
        $this->session = \Config\Services::session();
    }
    
    public function form($tipeKamarId)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }

        $tipeKamar = $this->tipeKamarModel->find($tipeKamarId);

        if (!$tipeKamar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $akomodasi = $this->akomodasiModel->find($tipeKamar['akomodasi_id']);

        // ambil tanggal
        $checkin  = $this->request->getGet('checkin') ?? date('Y-m-d');
        $checkout = $this->request->getGet('checkout') ?? date('Y-m-d', strtotime('+1 day'));

        // hitung malam
        $date1 = new \DateTime($checkin);
        $date2 = new \DateTime($checkout);
        $jumlahMalam = $date2->diff($date1)->days;

        // hitung harga
        $hargaDasar = $tipeKamar['harga_per_malam'] * $jumlahMalam;
        $biayaAdminPpn = (int) round($hargaDasar * 0.22);
        $totalAkhir = $hargaDasar + $biayaAdminPpn;

        // kirim ke view
        $data = [
            'tipe_kamar' => $tipeKamar,
            'akomodasi' => $akomodasi,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'jumlah_malam' => $jumlahMalam,

            // khusus tampilan
            'harga_dasar' => $hargaDasar,
            'biaya_admin_ppn' => $biayaAdminPpn,
            'total_akhir' => $totalAkhir,

            'user' => [
                'nama' => $this->session->get('nama'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('pemesanan', $data);
    }
    
    public function proses()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'tipe_kamar_id' => 'required|numeric',
            'tanggal_checkin' => 'required|valid_date[Y-m-d]',
            'tanggal_checkout' => 'required|valid_date[Y-m-d]',
            'metode_pembayaran' => 'required|in_list[tf_bank,e_wallet,qris,cash]'
        ];

        $metode = $this->request->getPost('metode_pembayaran');

        if ($metode !== 'cash') {
            $rules['bukti_pembayaran'] = 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|is_image[bukti_pembayaran]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $buktiPembayaran = null;

        if ($metode !== 'cash') {
            $file = $this->request->getFile('bukti_pembayaran');

            if ($file && $file->isValid()) {
                $namaFile = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads/bukti_pembayaran', $namaFile);
                $buktiPembayaran = $namaFile;
            }
        }
        
        $tipeKamar = $this->tipeKamarModel->find($this->request->getPost('tipe_kamar_id'));
        $akomodasi = $this->akomodasiModel->find($tipeKamar['akomodasi_id']);
        
        $checkin = new \DateTime($this->request->getPost('tanggal_checkin'));
        $checkout = new \DateTime($this->request->getPost('tanggal_checkout'));
        $jumlahMalam = $checkout->diff($checkin)->days;
        $hargaDasar = $tipeKamar['harga_per_malam'] * $jumlahMalam;
        $biayaAdminPpn = (int) round($hargaDasar * 0.22);
        $totalAkhir = $hargaDasar + $biayaAdminPpn;
        
       $data = [
            'user_id' => $this->session->get('user_id'),
            'akomodasi_id' => $akomodasi['id'],
            'tipe_kamar_id' => $tipeKamar['id'],
            'tanggal_checkin' => $this->request->getPost('tanggal_checkin'),
            'tanggal_checkout' => $this->request->getPost('tanggal_checkout'),
            'jumlah_malam' => $jumlahMalam,

            'biaya_admin_ppn' => $biayaAdminPpn,
            'total_harga' => $totalAkhir,

            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'bukti_pembayaran' => $buktiPembayaran,
            'status' => 'pending',
            'catatan' => $this->request->getPost('catatan')
        ];
        
        $pemesananId = $this->pemesananModel->insert($data);
        
        return redirect()->to('/pemesanan/sukses/' . $pemesananId);
    }
    
    public function sukses($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $pemesanan = $this->pemesananModel->getDetailPemesanan($id);
        
        if (!$pemesanan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Pastikan pemesanan milik user yang login
        if ($pemesanan['user_id'] != $this->session->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        return view('sukses', ['pemesanan' => $pemesanan]);
    }
}
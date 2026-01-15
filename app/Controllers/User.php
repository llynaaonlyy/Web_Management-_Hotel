<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PemesananModel;


class User extends BaseController
{
    protected $userModel;
    protected $session;
    protected $pemesananModel;

    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        $this->pemesananModel = new PemesananModel();

    }
    
    public function profil()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        return view('profil', ['user' => $user]);
    }
    
    public function updateProfil()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $userId = $this->session->get('user_id');
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'no_telp' => 'required|numeric|min_length[10]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp')
        ];
        
        $this->userModel->update($userId, $data);
        
        // Update session data
        $this->session->set('nama', $data['nama']);
        $this->session->set('email', $data['email']);
        
        return redirect()->to('/profil')->with('success', 'Profil berhasil diupdate!');
    }
    
    public function deleteAccount()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
        
        $userId = $this->session->get('user_id');
        
        // Hapus user dari database
        $this->userModel->delete($userId);
        
        // Destroy session
        $this->session->destroy();
        
        return redirect()->to('/')->with('success', 'Akun berhasil dihapus');
    }

    public function histori()
    {
        $userId = $this->session->get('user_id');
        
        // Ambil semua pemesanan user ini dari database
        $histori = $this->pemesananModel->getHistoriByUser($userId);
        
        $data = [
            'user' => [
                'nama' => $this->session->get('nama'),
                'email' => $this->session->get('email')
            ],
            'histori' => $histori
        ];
        
        return view('histori', $data);
    }
}
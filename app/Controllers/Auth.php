<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }
    
    public function login()
    {
        // if ($this->session->get('logged_in')) {
        //     return redirect()->to('/home');
        // }
        
        return view('auth/login');
    }
    
    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar');
        }
        
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }
        
        // 🔥 SET SESSION DULU
        $this->session->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        // 🔥 DEBUG (sementara)
        // dd(session()->get());

        // Redirect berdasarkan role
        if ($user['role'] === 'pegawai') {
            return redirect()->to('staff/dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user['nama']);
        }

        if ($user['role'] === 'admin') {
            return redirect()->to('admin/dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user['nama']);
        }

            return redirect()->to('/home')->with('success', 'Login berhasil! Selamat datang ' . $user['nama']);
    }
    
    public function register()
    {
        // Jika sudah login, redirect ke home
        // if ($this->session->get('logged_in')) {
        //     return redirect()->to('/home');
        // }
        
        return view('auth/register');
    }
    
    public function processRegister()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama minimal 3 karakter'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'no_telp' => [
                'rules' => 'required|numeric|min_length[10]',
                'errors' => [
                    'required' => 'No. telepon harus diisi',
                    'numeric' => 'No. telepon harus berupa angka',
                    'min_length' => 'No. telepon minimal 10 digit'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'pelanggan' // Default role pelanggan
        ];
        
        $this->userModel->insert($data);
        
        return redirect()->to('auth/login')->with('success', 'Registrasi berhasil! Silakan login');
    }
    
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('auth/login')->with('success', 'Logout berhasil');
    }

    public function forgotPassword()
    {
        return view('frontend-forgot-password');
    }

}
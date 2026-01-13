<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'password', 'no_telp', 'foto_profil', 'role'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'nama' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'no_telp' => 'permit_empty|numeric|min_length[10]',
        'role' => 'permit_empty|in_list[pelanggan,pegawai,admin]'
    ];
    
    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'no_telp' => [
            'numeric' => 'No. telepon harus berupa angka',
            'min_length' => 'No. telepon minimal 10 digit'
        ]
    ];
    
    // Get user by email
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    // Get user with role
    public function getUserWithRole($userId)
    {
        return $this->select('id, nama, email, no_telp, role, foto_profil')
                    ->find($userId);
    }
}
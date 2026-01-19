<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/** @var RouteCollection $routes */
$routes = Services::routes();

// Home
$routes->get('/', 'Index::index');

// Auth
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register/process', 'Auth::processRegister');

// Admin & Staff Dashboard
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/staff/dashboard', 'Staff::dashboard'); 
$routes->get('staff/dashboard', 'Staff::dashboard', ['filter' => 'pegawai']);

// Home (login required)
$routes->get('/home', 'Home::index');
$routes->get('/detail/(:num)', 'Home::detail/$1');
$routes->post('/search', 'Home::search');

// Profil pelanggan
$routes->get('/profil', 'User::profil');
$routes->post('/profil/update', 'User::updateProfil');
$routes->post('/profil/delete', 'User::deleteAccount');

// Profil staff
$routes->get('/staff/profil_staff', 'Staff::profilStaff');

// Pemesanan
$routes->get('/pemesanan/(:num)', 'PemesananController::form/$1');
$routes->post('/pemesanan/proses', 'PemesananController::proses');
$routes->get('/pemesanan/sukses/(:num)', 'PemesananController::sukses/$1');

// Route untuk daftar semua pemesanan
$routes->get('staff/pemesanan', 'Staff::daftarSemuaPemesanan');
$routes->get('staff/pemesanan/(:num)', 'Staff::detailPemesanan/$1');

// Route untuk halaman kelola kamar
$routes->get('staff/kelola-kamar', 'Staff::kelolaKamar');
$routes->post('staff/update-stok-kamar', 'Staff::updateStokKamar');
$routes->post('staff/update-status-kamar', 'Staff::updateStatusKamar');

// Route untuk data tamu
$routes->get('staff/data-tamu', 'Staff::dataTamu');
$routes->get('staff/tamu/(:num)', 'Staff::detailTamu/$1');

// Dashboard staff
$routes->get('staff/dashboard', 'Staff::dashboard');

//forgot password
$routes->get('forgot-password.html', 'Auth::forgotPassword');

// User Profile Routes (Require Login)
$routes->get('/profil', 'User::profil');
$routes->post('/profil/update', 'User::updateProfil');
$routes->post('/profil/delete', 'User::deleteAccount');
$routes->get('/histori', 'User::histori'); 

// Staff Update Status
$routes->post('staff/update-status', 'Staff::updateStatus');

// Dashboard Admin
$routes->get('dashboard', 'Admin::dashboard');
    
// Akomodasi
$routes->get('admin/akomodasi', 'Admin::akomodasi');
$routes->get('admin/akomodasi/tambah', 'Admin::akomodasiForm');
$routes->get('admin/akomodasi/edit/(:num)', 'Admin::akomodasiForm/$1');
$routes->post('admin/akomodasi/save', 'Admin::akomodasiSave');
$routes->get('admin/akomodasi/delete/(:num)', 'Admin::akomodasiDelete/$1');
$routes->get('admin/foto/delete/(:num)', 'Admin::fotoDelete/$1');
    
// Fasilitas
$routes->get('admin/fasilitas/(:num)', 'Admin::fasilitas/$1');
$routes->post('admin/fasilitas/save', 'Admin::fasilitasSave');
$routes->get('admin/fasilitas/delete/(:num)', 'Admin::fasilitasDelete/$1');
    
// Highlights
$routes->get('admin/highlights/(:num)', 'Admin::highlights/$1');
$routes->post('admin/highlights/save', 'Admin::highlightsSave');
$routes->get('admin/highlights/delete/(:num)', 'Admin::highlightsDelete/$1');
    
// Kebijakan
$routes->get('admin/kebijakan/(:num)', 'Admin::kebijakan/$1');
$routes->post('admin/kebijakan/save', 'Admin::kebijakanSave');
    
// Tipe Kamar
$routes->get('admin/tipe-kamar', 'Admin::tipeKamar');
$routes->get('admin/tipe-kamar/tambah', 'Admin::tipeKamarForm');
$routes->get('admin/tipe-kamar/edit/(:num)', 'Admin::tipeKamarForm/$1');
$routes->post('admin/tipe-kamar/save', 'Admin::tipeKamarSave');
$routes->get('admin/tipe-kamar/delete/(:num)', 'Admin::tipeKamarDelete/$1');
    
// Booking (Read Only)
$routes->get('admin/booking', 'Admin::booking');
    
// Users
$routes->get('admin/users', 'Admin::users');
$routes->post('admin/users/update', 'Admin::userUpdate');

// Laporan
$routes->get('admin/laporan', 'Admin::laporan');
$routes->post('admin/laporan/generate', 'Admin::laporanGenerate');
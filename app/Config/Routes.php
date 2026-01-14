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

// Pemesanan (pilih satu controller)
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

$routes->get('staff/data-tamu', 'Staff::dataTamu');
$routes->get('staff/tamu/(:num)', 'Staff::detailTamu/$1');

$routes->get('staff/dashboard', 'Staff::dashboard');

$routes->get('forgot-password.html', 'Auth::forgotPassword');

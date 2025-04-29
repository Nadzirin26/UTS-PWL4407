<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']); //Mengarahkan URL root ke method index di controller Home
                                                        //hanya bisa diakses jika sudah login

$routes->get('login', 'AuthController::login'); //Menampilkan halaman login.
$routes->post('login', 'AuthController::login'); //Memproses form login yang dikirimkan.
$routes->post('logout', 'AuthController::logout'); //Menangani proses logout user.

$routes->get('genpass', 'AuthController::generatepassword'); //Mengakses fungsi untuk generate password
$routes->get('/admin', 'Home::adminDashboard', ['filter' => 'auth']); //Akses ke dashboard admin, hanya jika admin sudah login
$routes->get('/user', 'Home::userDashboard', ['filter' => 'auth']); //Akses ke dashboard user, juga hanya untuk user yang sudah login.

$routes->get('/users', 'Home::users', ['filter' => 'auth']);        // Menampilkan daftar user
$routes->get('/users/create', 'Home::create', ['filter' => 'auth']); // Menampilkan form tambah user
$routes->post('/users/store', 'Home::store');                        // Menyimpan user baru
$routes->get('/users/edit/(:any)', 'Home::edit/$1');                 // Edit user berdasarkan username
$routes->post('/users/update/(:any)', 'Home::update/$1');            // Update user berdasarkan username
$routes->get('/users/delete/(:any)', 'Home::delete/$1');             // Hapus user berdasarkan username



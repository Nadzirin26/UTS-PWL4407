<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('home', 'Home::index', ['filter' => 'auth']); 
                                                        
$routes->get('/', 'ProductController::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login'); //Menampilkan halaman login.
$routes->post('login', 'AuthController::login'); //Memproses form login yang dikirimkan.
$routes->post('logout', 'AuthController::logout'); //Menangani proses logout user.

$routes->get('genpass', 'AuthController::generatepassword'); //Mengakses fungsi untuk generate password
$routes->get('/admin', 'Home::adminDashboard', ['filter' => 'auth']); //Akses ke dashboard admin, hanya jika admin sudah login
$routes->get('/user', 'Home::userDashboard', ['filter' => 'auth']); //Akses ke dashboard user, juga hanya untuk user yang sudah login.

// product
$routes->group('product', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProductController::index');
    $routes->post('', 'ProductController::create');
    $routes->post('edit/(:any)', 'ProductController::edit/$1');
    $routes->get('delete/(:any)', 'ProductController::delete/$1');
    $routes->get('download', 'ProductController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);

$routes->get('/users', 'Home::users', ['filter' => 'auth']);        // Menampilkan daftar user
$routes->get('/users/create', 'Home::create', ['filter' => 'auth']); // Menampilkan form tambah user
$routes->post('/users/store', 'Home::store');                        // Menyimpan user baru
$routes->get('/users/edit/(:any)', 'Home::edit/$1');                 // Edit user berdasarkan username
$routes->post('/users/update/(:any)', 'Home::update/$1');            // Update user berdasarkan username
$routes->get('/users/delete/(:any)', 'Home::delete/$1');             // Hapus user berdasarkan username



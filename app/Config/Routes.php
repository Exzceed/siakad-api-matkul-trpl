<?php

namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();
$routes->group('api', function ($routes) {

    // Semua mata kuliah
    $routes->get(
        'matkul',
        '\App\Controllers\Api\MataKuliah::index'
    );

    // Mata kuliah berdasarkan semester
    $routes->get(
        'matkul/semester/(:num)',
        '\App\Controllers\Api\MataKuliah::semester/$1'
    );

    // Detail mata kuliah
    $routes->get(
        'matkul/detail/(:segment)',
        '\App\Controllers\Api\MataKuliah::detail/$1'
    );

    // Tambah mata kuliah
    $routes->post(
        'matkul',
        '\App\Controllers\Api\MataKuliah::create'
    );

    // Update mata kuliah
    $routes->put(
        'matkul/(:segment)',
        '\App\Controllers\Api\MataKuliah::update/$1'
    );

    // Hapus mata kuliah
    $routes->delete(
        'matkul/(:segment)',
        '\App\Controllers\Api\MataKuliah::delete/$1'
    );

    

});
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base Path - İLK ÖNCE BU
require_once __DIR__ . '/../config/base_path.php';

// Autoload Core Files
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// Initialize Router
$router = new Router();

// Public Routes
$router->get('/', 'HomeController', 'index');
$router->get('/video/{slug}', 'VideoController', 'show');
$router->get('/kategori/{slug}', 'CategoryController', 'show');
$router->get('/arama', 'SearchController', 'fullSearch');

// Ajax API Routes
$router->get('/api/search', 'SearchController', 'liveSearch');

// Admin Auth Routes
$router->get('/admin/login', 'AuthController', 'login');
$router->post('/admin/login', 'AuthController', 'login');
$router->get('/admin/logout', 'AuthController', 'logout');

// Admin Video Routes
$router->get('/admin/videos', 'VideoController', 'adminIndex');
$router->get('/admin/videos/create', 'VideoController', 'create');
$router->post('/admin/videos/create', 'VideoController', 'create');
$router->get('/admin/videos/edit/{id}', 'VideoController', 'edit');
$router->post('/admin/videos/edit/{id}', 'VideoController', 'edit');
$router->get('/admin/videos/delete/{id}', 'VideoController', 'delete');
$router->get('/admin/videos/permanent-delete/{id}', 'VideoController', 'permanentDelete');
$router->get('/admin/videos/toggle/{id}', 'VideoController', 'toggleStatus');

// Admin Category Routes
$router->get('/admin/categories', 'CategoryController', 'adminIndex');
$router->get('/admin/categories/create', 'CategoryController', 'create');
$router->post('/admin/categories/create', 'CategoryController', 'create');
$router->get('/admin/categories/edit/{id}', 'CategoryController', 'edit');
$router->post('/admin/categories/edit/{id}', 'CategoryController', 'edit');
$router->get('/admin/categories/delete/{id}', 'CategoryController', 'delete');

// Dispatch
$router->dispatch();
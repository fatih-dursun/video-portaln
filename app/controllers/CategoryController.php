<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Category.php';

class CategoryController extends Controller {
    private $videoModel;
    private $categoryModel;

    public function __construct() {
        session_start();
        $this->videoModel = new Video();
        $this->categoryModel = new Category();
    }

    public function show($slug) {
        $category = $this->categoryModel->getBySlug($slug);
        
        if (!$category) {
            $this->redirect('/');
            return;
        }

        $videos = $this->videoModel->getByCategory($category['id']);
        $categories = $this->categoryModel->getAllWithVideoCount();

        $this->view('public/category', [
            'category' => $category,
            'videos' => $videos,
            'categories' => $categories,
            'pageTitle' => $category['name']
        ]);
    }

    public function adminIndex() {
        $this->checkAuth();
        $categories = $this->categoryModel->getAllWithVideoCount();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function create() {
        $this->checkAuth();
        $this->requireAdmin(); // Sadece admin kategori ekleyebilir
        
        if ($this->isPost()) {
            $errors = $this->validateRequired([
                'name' => 'Kategori Adı',
                'background_color' => 'Arka Plan Rengi',
                'text_color' => 'Yazı Rengi'
            ]);

            if (empty($errors)) {
                $this->categoryModel->create([
                    'name' => $_POST['name'],
                    'slug' => $this->slugify($_POST['name']),
                    'background_color' => $_POST['background_color'],
                    'text_color' => $_POST['text_color'],
                    'created_by' => $_SESSION['admin_id'] // Ekleyen admin
                ]);

                $this->redirect('/admin/categories');
                return;
            }
        }

        $this->view('admin/categories/create', [
            'errors' => $errors ?? [],
            'colorPresets' => Category::$colorPresets
        ]);
    }

    public function edit($id) {
        $this->checkAuth();
        $this->requireAdmin(); // Sadece admin düzenleyebilir
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            $this->redirect('/admin/categories');
            return;
        }

        if ($this->isPost()) {
            $this->categoryModel->update($id, [
                'name' => $_POST['name'],
                'slug' => $this->slugify($_POST['name']),
                'background_color' => $_POST['background_color'],
                'text_color' => $_POST['text_color']
            ]);

            $this->redirect('/admin/categories');
            return;
        }

        $this->view('admin/categories/edit', [
            'category' => $category,
            'colorPresets' => Category::$colorPresets
        ]);
    }

    public function delete($id) {
        $this->checkAuth();
        $this->requireAdmin(); // Sadece admin silebilir
        
        $this->categoryModel->delete($id);
        $this->redirect('/admin/categories');
    }

    private function checkAuth() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
            exit;
        }
    }
}
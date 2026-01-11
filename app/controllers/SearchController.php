<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Category.php';

class SearchController extends Controller {
    private $videoModel;
    private $categoryModel;

    public function __construct() {
        $this->videoModel = new Video();
        $this->categoryModel = new Category();
    }

    public function liveSearch() {
        $query = $this->input('q', '');
        
        if (strlen($query) < 2) {
            $this->json(['results' => []]);
            return;
        }

        $results = $this->videoModel->search($query, 5);
        $this->json(['results' => $results]);
    }

    public function fullSearch() {
        $query = $this->input('q', '');
        $videos = [];
        
        if (strlen($query) >= 2) {
            $videos = $this->videoModel->search($query, 50);
        }

        $categories = $this->categoryModel->getAllWithVideoCount();

        $this->view('public/search', [
            'query' => $query,
            'videos' => $videos,
            'categories' => $categories,
            'pageTitle' => 'Arama: ' . $query
        ]);
    }
}
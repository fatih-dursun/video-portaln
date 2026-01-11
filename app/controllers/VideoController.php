<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/ImageGenerator.php';

class VideoController extends Controller {
    private $videoModel;
    private $categoryModel;

    public function __construct() {
        session_start();
        $this->videoModel = new Video();
        $this->categoryModel = new Category();
    }

    public function show($slug) {
        $video = $this->videoModel->getBySlug($slug);
        
        if (!$video) {
            $this->redirect('/');
            return;
        }

        $this->videoModel->incrementView($video['id']);

        $relatedVideos = $this->videoModel->getByCategory($video['category_id'], 4);
        $categories = $this->categoryModel->getAllWithVideoCount();

        $this->view('public/video', [
            'video' => $video,
            'relatedVideos' => $relatedVideos,
            'categories' => $categories,
            'pageTitle' => $video['title']
        ]);
    }

    public function adminIndex() {
        $this->checkAuth();
        $videos = $this->videoModel->getAllForAdmin();
        $this->view('admin/videos/index', ['videos' => $videos]);
    }

    public function create() {
        $this->checkAuth();
        
        if ($this->isPost()) {
            $errors = $this->validateRequired([
                'title' => 'Video Başlığı',
                'category_id' => 'Kategori',
                'description' => 'Açıklama'
            ]);

            if (empty($errors)) {
                $videoPath = $this->uploadFile($_FILES['video'], 'videos', ['mp4', 'webm', 'ogg']);
                $thumbnailPath = $this->uploadFile($_FILES['thumbnail'], 'thumbnails', ['jpg', 'jpeg', 'png']);

                if (!$videoPath || !$thumbnailPath) {
                    $errors['file'] = 'Video veya thumbnail yüklenemedi';
                } else {
                    $title = $_POST['title'];
                    $slug = $this->slugify($title);
                    $featuredText = !empty($_POST['featured_text']) ? $_POST['featured_text'] : $title;
                    
                    $category = $this->categoryModel->find($_POST['category_id']);
                    
                    $generator = new ImageGenerator();
                    $featuredImageName = uniqid() . '_' . time() . '.jpg';
                    $featuredImagePath = __DIR__ . '/../../public/uploads/featured/' . $featuredImageName;
                    
                    if (!is_dir(dirname($featuredImagePath))) {
                        mkdir(dirname($featuredImagePath), 0755, true);
                    }
                    
                    $generatedPath = $generator->generateFeaturedImage(
                        $featuredText,
                        $category['background_color'],
                        $category['text_color'],
                        $featuredImagePath
                    );

                    $videoId = $this->videoModel->create([
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $_POST['description'],
                        'featured_text' => $featuredText,
                        'video_path' => $videoPath,
                        'thumbnail_path' => $thumbnailPath,
                        'featured_image_path' => $generatedPath,
                        'category_id' => $_POST['category_id'],
                        'created_by' => $_SESSION['admin_id'], // Ekleyen admin
                        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                        'status' => $_POST['status'] ?? 'active'
                    ]);

                    $this->redirect('/admin/videos');
                    return;
                }
            }
        }

        $categories = $this->categoryModel->all();
        $this->view('admin/videos/create', ['categories' => $categories, 'errors' => $errors ?? []]);
    }

    public function edit($id) {
        $this->checkAuth();
        $video = $this->videoModel->find($id);
        
        if (!$video) {
            $this->redirect('/admin/videos');
            return;
        }
        
        // Editor ise sadece kendi videosunu düzenleyebilir
        if ($this->isEditor() && $video['created_by'] != $_SESSION['admin_id']) {
            die('Bu videoyu düzenleme yetkiniz yok!');
        }

        if ($this->isPost()) {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->slugify($_POST['title']),
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'status' => $_POST['status']
            ];

            if (!empty($_POST['featured_text']) && $_POST['featured_text'] !== $video['featured_text']) {
                $data['featured_text'] = $_POST['featured_text'];
                
                $category = $this->categoryModel->find($_POST['category_id']);
                $generator = new ImageGenerator();
                $featuredImageName = uniqid() . '_' . time() . '.jpg';
                $featuredImagePath = __DIR__ . '/../../public/uploads/featured/' . $featuredImageName;
                
                $generatedPath = $generator->generateFeaturedImage(
                    $_POST['featured_text'],
                    $category['background_color'],
                    $category['text_color'],
                    $featuredImagePath
                );
                
                $data['featured_image_path'] = $generatedPath;
            }

            if (!empty($_FILES['video']['name'])) {
                $videoPath = $this->uploadFile($_FILES['video'], 'videos', ['mp4', 'webm', 'ogg']);
                if ($videoPath) $data['video_path'] = $videoPath;
            }

            if (!empty($_FILES['thumbnail']['name'])) {
                $thumbnailPath = $this->uploadFile($_FILES['thumbnail'], 'thumbnails', ['jpg', 'jpeg', 'png']);
                if ($thumbnailPath) $data['thumbnail_path'] = $thumbnailPath;
            }

            $this->videoModel->update($id, $data);
            $this->redirect('/admin/videos');
            return;
        }

        $categories = $this->categoryModel->all();
        $this->view('admin/videos/edit', ['video' => $video, 'categories' => $categories]);
    }

    public function delete($id) {
        $this->checkAuth();
        
        // Editor ise sadece kendi videosunu silebilir
        if ($this->isEditor()) {
            $video = $this->videoModel->find($id);
            if ($video['created_by'] != $_SESSION['admin_id']) {
                die('Bu videoyu silme yetkiniz yok!');
            }
        }
        
        $this->videoModel->softDelete($id);
        $this->redirect('/admin/videos');
    }

    public function permanentDelete($id) {
        $this->checkAuth();
        $this->videoModel->delete($id);
        $this->redirect('/admin/videos');
    }

    public function toggleStatus($id) {
        $this->checkAuth();
        
        // Editor ise sadece kendi videosunu değiştirebilir
        if ($this->isEditor()) {
            $video = $this->videoModel->find($id);
            if ($video['created_by'] != $_SESSION['admin_id']) {
                die('Bu videoyu düzenleme yetkiniz yok!');
            }
        }
        
        $video = $this->videoModel->find($id);
        
        $newStatus = $video['status'] === 'active' ? 'passive' : 'active';
        $this->videoModel->update($id, ['status' => $newStatus]);
        
        $this->redirect('/admin/videos');
    }

    private function checkAuth() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
            exit;
        }
    }
}
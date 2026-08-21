<?php
/**
 * GenzNewz — Admin Article Management Controller
 */

class AdminArticleController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $status = $_GET['status'] ?? 'all';
        $page = max(1, (int)($_GET['page'] ?? 1));
        
        $where = "1=1";
        $params = [];
        if ($status !== 'all') {
            $where = "status = ?";
            $params = [$status];
        }

        $articles = Article::paginate($page, 15, $where, $params, 'id DESC');
        
        // Enrich data with reporter and category
        $items = $articles['data'];
        foreach ($items as &$item) {
            $cat = Category::find($item['category_id']);
            $rep = User::find($item['reporter_id']);
            $item['category_name'] = $cat['name'] ?? 'N/A';
            $item['reporter_name'] = $rep['name'] ?? 'N/A';
        }
        $articles['data'] = $items;

        $this->view('admin/views/articles/index', [
            'pageTitle' => 'সংবাদ ও প্রতিবেদন ব্যবস্থাপনা',
            'articles' => $articles,
            'currentStatus' => $status
        ]);
    }

    public function pending(): void {
        $_GET['status'] = 'submitted';
        $this->index();
    }

    public function published(): void {
        $_GET['status'] = 'published';
        $this->index();
    }

    public function show(int|string $id): void {
        Auth::requireAdmin();
        $article = Article::find($id);

        if (!$article) {
            $this->error404('প্রতিবেদনটি পাওয়া যায়নি।');
            return;
        }

        $category = Category::find($article['category_id']);
        $reporter = User::find($article['reporter_id']);
        $profile = ReporterProfile::findByUserId($article['reporter_id']);

        $this->view('admin/views/articles/view', [
            'pageTitle' => 'প্রতিবেদন পর্যালোচনা: ' . $article['title'],
            'article' => $article,
            'category' => $category,
            'reporter' => $reporter,
            'profile' => $profile
        ]);
    }

    public function create(): void {
        Auth::requireAdmin();
        $categories = Category::getActive();
        $editions = Edition::all('edition_date DESC', 10);

        $this->view('admin/views/articles/create', [
            'pageTitle' => 'নতুন সংবাদ প্রতিবেদন লিখুন',
            'categories' => $categories,
            'editions' => $editions
        ]);
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $title = trim($_POST['title'] ?? '');
        $subheadline = trim($_POST['subheadline'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 1);
        $editionId = !empty($_POST['edition_id']) ? (int)$_POST['edition_id'] : null;
        $location = trim($_POST['location'] ?? 'কলকাতা');
        $authorName = trim($_POST['author_name'] ?? Auth::user()['name']);
        $shortDesc = trim($_POST['short_description'] ?? '');
        $content = $_POST['content'] ?? '';
        $isBreaking = isset($_POST['is_breaking']) ? 1 : 0;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isTopStory = isset($_POST['is_top_story']) ? 1 : 0;
        $status = $_POST['status'] ?? 'published';

        $featuredImage = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $res = Upload::file($_FILES['featured_image'], 'uploads/articles', 10);
            if ($res['success']) {
                $featuredImage = $res['file_path'];
            }
        }

        $slug = Helper::slugify($title) . '-' . time();

        $articleId = Article::create([
            'reporter_id' => Auth::id(),
            'category_id' => $categoryId,
            'edition_id' => $editionId,
            'title' => $title,
            'subheadline' => $subheadline,
            'slug' => $slug,
            'short_description' => $shortDesc,
            'content' => $content,
            'featured_image' => $featuredImage,
            'author_name' => $authorName,
            'location' => $location,
            'status' => $status,
            'is_breaking' => $isBreaking,
            'is_featured' => $isFeatured,
            'is_top_story' => $isTopStory,
            'views_count' => 0,
            'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null
        ]);

        Auth::logActivity('ARTICLE_CREATED', "Admin created article: {$title} (ID: {$articleId})");
        Session::setFlash('success', 'সংবাদটি সফলভাবে তৈরি ও সংরক্ষিত হয়েছে।');
        $this->redirect('/admin/articles');
    }

    public function edit(int|string $id): void {
        Auth::requireAdmin();
        $article = Article::find($id);

        if (!$article) {
            $this->error404('প্রতিবেদন পাওয়া যায়নি।');
            return;
        }

        $categories = Category::getActive();
        $editions = Edition::all('edition_date DESC', 10);

        $this->view('admin/views/articles/edit', [
            'pageTitle' => 'সংবাদ সম্পাদনা: ' . $article['title'],
            'article' => $article,
            'categories' => $categories,
            'editions' => $editions
        ]);
    }

    public function update(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $article = Article::find($id);
        if (!$article) {
            $this->error404('প্রতিবেদন পাওয়া যায়নি।');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $subheadline = trim($_POST['subheadline'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? $article['category_id']);
        $editionId = !empty($_POST['edition_id']) ? (int)$_POST['edition_id'] : null;
        $location = trim($_POST['location'] ?? 'কলকাতা');
        $authorName = trim($_POST['author_name'] ?? $article['author_name']);
        $shortDesc = trim($_POST['short_description'] ?? '');
        $content = $_POST['content'] ?? '';
        $isBreaking = isset($_POST['is_breaking']) ? 1 : 0;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isTopStory = isset($_POST['is_top_story']) ? 1 : 0;
        $status = $_POST['status'] ?? $article['status'];

        $data = [
            'title' => $title,
            'subheadline' => $subheadline,
            'category_id' => $categoryId,
            'edition_id' => $editionId,
            'location' => $location,
            'author_name' => $authorName,
            'short_description' => $shortDesc,
            'content' => $content,
            'is_breaking' => $isBreaking,
            'is_featured' => $isFeatured,
            'is_top_story' => $isTopStory,
            'status' => $status
        ];

        if (!empty($_FILES['featured_image']['name'])) {
            $res = Upload::file($_FILES['featured_image'], 'uploads/articles', 10);
            if ($res['success']) {
                $data['featured_image'] = $res['file_path'];
            }
        }

        if ($status === 'published' && empty($article['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        Article::update($id, $data);
        Auth::logActivity('ARTICLE_UPDATED', "Admin updated article: {$title} (ID: {$id})");
        Session::setFlash('success', 'প্রতিবেদনটি সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect('/admin/articles');
    }

    public function approve(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $article = Article::find($id);
        if ($article) {
            Article::update($id, [
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'rejection_reason' => null
            ]);

            // Notify reporter
            Notification::createNotification(
                $article['reporter_id'],
                'সংবাদ প্রকাশিত হয়েছে',
                "আপনার জমা দেওয়া প্রতিবেদন '{$article['title']}' সফলভাবে অনুমোদিত ও প্রকাশিত হয়েছে।",
                'success',
                '/reporter/articles'
            );

            Auth::logActivity('ARTICLE_APPROVED', "Approved & published article: {$article['title']} (ID: {$id})");
            Session::setFlash('success', 'প্রতিবেদনটি সফলভাবে অনুমোদিত ও প্রকাশিত হয়েছে।');
        }

        $this->redirect('/admin/articles');
    }

    public function reject(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $article = Article::find($id);
        $reason = trim($_POST['rejection_reason'] ?? 'তথ্য বা লেখার মানে অসম্পূর্ণতা রয়েছে।');

        if ($article) {
            Article::update($id, [
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);

            // Notify reporter
            Notification::createNotification(
                $article['reporter_id'],
                'প্রতিবেদন সংশোধন প্রয়োজন (বাতিল)',
                "আপনার জমা দেওয়া প্রতিবেদন '{$article['title']}' সংশোধনের জন্য ফেরত পাঠানো হয়েছে। কারণ: {$reason}",
                'warning',
                '/reporter/articles'
            );

            Auth::logActivity('ARTICLE_REJECTED', "Rejected article: {$article['title']} (ID: {$id})");
            Session::setFlash('warning', 'প্রতিবেদনটি প্রত্যাখ্যাত হয়েছে এবং রিপোর্টারকে অবহিত করা হয়েছে।');
        }

        $this->redirect('/admin/articles');
    }

    public function delete(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $article = Article::find($id);
        if ($article) {
            Article::delete($id);
            Auth::logActivity('ARTICLE_DELETED', "Deleted article: {$article['title']} (ID: {$id})");
            Session::setFlash('success', 'প্রতিবেদনটি মুছে ফেলা হয়েছে।');
        }

        $this->redirect('/admin/articles');
    }
}

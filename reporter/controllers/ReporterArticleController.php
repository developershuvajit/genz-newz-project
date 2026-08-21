<?php
/**
 * GenzNewz — Reporter Article Controller
 */

declare(strict_types=1);

class ReporterArticleController extends Controller {
    public function index(): void {
        Auth::requireReporter();

        $userId = Auth::id();
        $status = $_GET['status'] ?? 'all';
        $page = max(1, (int)($_GET['page'] ?? 1));

        $where = "reporter_id = ?";
        $params = [$userId];

        if ($status !== 'all') {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $articles = Article::paginate($page, 12, $where, $params, 'id DESC');

        $items = $articles['data'];
        foreach ($items as &$item) {
            $cat = Category::find($item['category_id']);
            $item['category_name'] = $cat['name'] ?? 'সাধারণ';
        }
        $articles['data'] = $items;

        $this->view('reporter/views/articles/index', [
            'pageTitle' => 'আমার সংবাদ প্রতিবেদন তালিকা',
            'articles' => $articles,
            'currentStatus' => $status
        ]);
    }

    public function create(): void {
        Auth::requireReporter();
        $categories = Category::getActive();

        $this->view('reporter/views/articles/create', [
            'pageTitle' => 'নতুন সংবাদ প্রতিবেদন তৈরি করুন',
            'categories' => $categories
        ]);
    }

    public function store(): void {
        Auth::requireReporter();
        CSRF::verify();

        $title = trim($_POST['title'] ?? '');
        $subheadline = trim($_POST['subheadline'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 1);
        $location = trim($_POST['location'] ?? 'কলকাতা');
        $shortDesc = trim($_POST['short_description'] ?? '');
        $content = $_POST['content'] ?? '';
        $actionType = $_POST['action_type'] ?? 'submit'; // 'draft' or 'submit'

        $status = ($actionType === 'draft') ? 'draft' : 'submitted';

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
            'edition_id' => null,
            'title' => $title,
            'subheadline' => $subheadline,
            'slug' => $slug,
            'short_description' => $shortDesc,
            'content' => $content,
            'featured_image' => $featuredImage,
            'author_name' => Auth::user()['name'],
            'location' => $location,
            'status' => $status,
            'rejection_reason' => null,
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 0,
            'published_at' => null
        ]);

        // If submitted, notify super admin
        if ($status === 'submitted') {
            $admin = User::findBy('role', ROLE_ADMIN);
            if ($admin) {
                Notification::createNotification(
                    $admin['id'],
                    'নতুন সংবাদ পর্যালোচনার জন্য জমা পড়েছে',
                    "রিপোর্টার " . Auth::user()['name'] . " একটি নতুন প্রতিবেদন '{$title}' জমা দিয়েছেন।",
                    'review',
                    '/admin/articles/pending'
                );
            }
        }

        Auth::logActivity('REPORTER_ARTICLE_CREATED', "Reporter created article: {$title} (Status: {$status})");
        Session::setFlash('success', ($status === 'draft') ? 'খসড়া হিসেবে সংরক্ষিত হয়েছে।' : 'প্রতিবেদনটি পর্যালোচনার জন্য জমা দেওয়া হয়েছে।');
        $this->redirect('/reporter/articles');
    }

    public function edit(int|string $id): void {
        Auth::requireReporter();
        $article = Article::find($id);

        if (!$article || (int)$article['reporter_id'] !== Auth::id()) {
            $this->error403('এই প্রতিবেদনটি সম্পাদনার অনুমতি আপনার নেই।');
            return;
        }

        $categories = Category::getActive();

        $this->view('reporter/views/articles/edit', [
            'pageTitle' => 'প্রতিবেদন সম্পাদনা: ' . $article['title'],
            'article' => $article,
            'categories' => $categories
        ]);
    }

    public function update(int|string $id): void {
        Auth::requireReporter();
        CSRF::verify();

        $article = Article::find($id);
        if (!$article || (int)$article['reporter_id'] !== Auth::id()) {
            $this->error403('অনুমতি নেই।');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $subheadline = trim($_POST['subheadline'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? $article['category_id']);
        $location = trim($_POST['location'] ?? 'কলকাতা');
        $shortDesc = trim($_POST['short_description'] ?? '');
        $content = $_POST['content'] ?? '';
        $actionType = $_POST['action_type'] ?? 'submit';

        $status = ($actionType === 'draft') ? 'draft' : 'submitted';

        $data = [
            'title' => $title,
            'subheadline' => $subheadline,
            'category_id' => $categoryId,
            'location' => $location,
            'short_description' => $shortDesc,
            'content' => $content,
            'status' => $status,
            'rejection_reason' => null
        ];

        if (!empty($_FILES['featured_image']['name'])) {
            $res = Upload::file($_FILES['featured_image'], 'uploads/articles', 10);
            if ($res['success']) {
                $data['featured_image'] = $res['file_path'];
            }
        }

        Article::update($id, $data);

        if ($status === 'submitted') {
            $admin = User::findBy('role', ROLE_ADMIN);
            if ($admin) {
                Notification::createNotification(
                    $admin['id'],
                    'সংশোধিত সংবাদ পর্যালোচনার জন্য জমা পড়েছে',
                    "রিপোর্টার " . Auth::user()['name'] . " '{$title}' প্রতিবেদনটি পুনঃজমা দিয়েছেন।",
                    'review',
                    '/admin/articles/pending'
                );
            }
        }

        Auth::logActivity('REPORTER_ARTICLE_UPDATED', "Reporter updated article: {$title} (Status: {$status})");
        Session::setFlash('success', 'প্রতিবেদনটি সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect('/reporter/articles');
    }

    public function show(int|string $id): void {
        Auth::requireReporter();
        $article = Article::find($id);

        if (!$article || (int)$article['reporter_id'] !== Auth::id()) {
            $this->error403('অনুমতি নেই।');
            return;
        }

        $category = Category::find($article['category_id']);

        $this->view('reporter/views/articles/view', [
            'pageTitle' => 'প্রতিবেদন বিবরণ: ' . $article['title'],
            'article' => $article,
            'category' => $category
        ]);
    }

    public function submit(int|string $id): void {
        Auth::requireReporter();
        CSRF::verify();

        $article = Article::find($id);
        if ($article && (int)$article['reporter_id'] === Auth::id()) {
            Article::update($id, ['status' => 'submitted', 'rejection_reason' => null]);
            Session::setFlash('success', 'প্রতিবেদনটি পর্যালোচনার জন্য পাঠানো হয়েছে।');
        }

        $this->redirect('/reporter/articles');
    }

    public function delete(int|string $id): void {
        Auth::requireReporter();
        CSRF::verify();

        $article = Article::find($id);
        if ($article && (int)$article['reporter_id'] === Auth::id() && in_array($article['status'], ['draft', 'rejected'])) {
            Article::delete($id);
            Session::setFlash('success', 'খসড়াটি মুছে ফেলা হয়েছে।');
        }

        $this->redirect('/reporter/articles');
    }
}

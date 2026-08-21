<?php
/**
 * GenzNewz — Admin Category Controller
 */

declare(strict_types=1);

class AdminCategoryController extends Controller {
    public function index(): void {
        Auth::requireAdmin();
        $categories = Category::all('sort_order ASC, id ASC');

        $this->view('admin/views/categories/index', [
            'pageTitle' => 'সংবাদ বিভাগ / ক্যাটাগরি ব্যবস্থাপনা',
            'categories' => $categories
        ]);
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $name = trim($_POST['name'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        $slug = Helper::slugify(!empty($nameEn) ? $nameEn : $name);

        Category::create([
            'name' => $name,
            'name_en' => $nameEn,
            'slug' => $slug,
            'description' => $description,
            'sort_order' => $sortOrder,
            'status' => 'active'
        ]);

        Auth::logActivity('CATEGORY_CREATED', "Created category: {$name}");
        Session::setFlash('success', 'নতুন ক্যাটাগরি যুক্ত করা হয়েছে।');
        $this->redirect('/admin/categories');
    }

    public function delete(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        Category::delete($id);
        Auth::logActivity('CATEGORY_DELETED', "Deleted category ID: {$id}");
        Session::setFlash('success', 'ক্যাটাগরিটি মুছে ফেলা হয়েছে।');
        $this->redirect('/admin/categories');
    }
}

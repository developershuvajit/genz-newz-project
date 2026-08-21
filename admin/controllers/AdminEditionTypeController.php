<?php
/**
 * GenzNewz — Admin Edition Type Controller
 */

declare(strict_types=1);

class AdminEditionTypeController extends Controller {
    public function index(): void {
        Auth::requireAdmin();
        $editionTypes = EditionType::all('sort_order ASC, id ASC');

        $this->view('admin/views/edition_types/index', [
            'pageTitle' => 'ই-পেপার সংস্করণ ধরণ ব্যবস্থাপনা',
            'editionTypes' => $editionTypes
        ]);
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $name = trim($_POST['name'] ?? '');
        $slug = Helper::slugify(trim($_POST['slug'] ?? $name));
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        EditionType::create([
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
            'status' => 'active'
        ]);

        Auth::logActivity('EDITION_TYPE_CREATED', "Created edition type: {$name}");
        Session::setFlash('success', 'নতুন সংস্করণ ধরণ যুক্ত হয়েছে।');
        $this->redirect('/admin/edition-types');
    }

    public function delete(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        EditionType::delete($id);
        Auth::logActivity('EDITION_TYPE_DELETED', "Deleted edition type ID: {$id}");
        Session::setFlash('success', 'সংস্করণ ধরণটি মুছে ফেলা হয়েছে।');
        $this->redirect('/admin/edition-types');
    }
}

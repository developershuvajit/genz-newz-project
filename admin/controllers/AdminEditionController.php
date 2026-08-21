<?php
/**
 * GenzNewz — Admin Edition Controller
 */

declare(strict_types=1);

class AdminEditionController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $editions = Edition::paginate($page, 15, '1=1', [], 'edition_date DESC, id DESC');
        
        // Fetch edition type and page counts
        $items = $editions['data'];
        foreach ($items as &$item) {
            $type = EditionType::find($item['edition_type_id']);
            $item['edition_type_name'] = $type['name'] ?? 'ডিফল্ট';
            $item['page_count'] = EditionPage::count("edition_id = ?", [$item['id']]);
        }
        $editions['data'] = $items;

        $this->view('admin/views/editions/index', [
            'pageTitle' => 'ই-পেপার সংস্করণ তালিকা',
            'editions' => $editions
        ]);
    }

    public function create(): void {
        Auth::requireAdmin();
        $editionTypes = EditionType::getActive();

        $this->view('admin/views/editions/create', [
            'pageTitle' => 'নতুন ই-পেপার সংস্করণ তৈরি করুন',
            'editionTypes' => $editionTypes
        ]);
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $title = trim($_POST['title'] ?? '');
        $date = trim($_POST['edition_date'] ?? date('Y-m-d'));
        $typeId = (int)($_POST['edition_type_id'] ?? 1);
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = $_POST['status'] ?? 'published';

        $slug = 'edition-' . date('d-m-Y', strtotime($date)) . '-' . time();

        $editionId = Edition::create([
            'title' => $title,
            'slug' => $slug,
            'edition_date' => $date,
            'edition_type_id' => $typeId,
            'description' => $description,
            'cover_image' => '/storage/pages/thumb/page_1.svg',
            'pdf_file' => null,
            'status' => $status,
            'is_featured' => $isFeatured,
            'created_by' => Auth::id()
        ]);

        Auth::logActivity('EDITION_CREATED', "Created edition: {$title} (ID: {$editionId})");
        Session::setFlash('success', 'সংস্করণটি সফলভাবে তৈরি হয়েছে। এবার পাতা যুক্ত করুন।');
        $this->redirect("/admin/editions/{$editionId}/pages");
    }

    public function edit(int|string $id): void {
        Auth::requireAdmin();
        $edition = Edition::find($id);
        if (!$edition) {
            $this->error404('সংস্করণ পাওয়া যায়নি।');
            return;
        }

        $editionTypes = EditionType::getActive();

        $this->view('admin/views/editions/edit', [
            'pageTitle' => 'সংস্করণ সম্পাদনা: ' . $edition['title'],
            'edition' => $edition,
            'editionTypes' => $editionTypes
        ]);
    }

    public function update(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $edition = Edition::find($id);
        if (!$edition) {
            $this->error404('সংস্করণ পাওয়া যায়নি।');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $date = trim($_POST['edition_date'] ?? $edition['edition_date']);
        $typeId = (int)($_POST['edition_type_id'] ?? $edition['edition_type_id']);
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = $_POST['status'] ?? $edition['status'];

        Edition::update($id, [
            'title' => $title,
            'edition_date' => $date,
            'edition_type_id' => $typeId,
            'description' => $description,
            'is_featured' => $isFeatured,
            'status' => $status
        ]);

        Auth::logActivity('EDITION_UPDATED', "Updated edition: {$title} (ID: {$id})");
        Session::setFlash('success', 'সংস্করণ সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect('/admin/editions');
    }

    public function delete(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $edition = Edition::find($id);
        if ($edition) {
            Edition::delete($id);
            Auth::logActivity('EDITION_DELETED', "Deleted edition: {$edition['title']} (ID: {$id})");
            Session::setFlash('success', 'সংস্করণটি মুছে ফেলা হয়েছে।');
        }

        $this->redirect('/admin/editions');
    }
}

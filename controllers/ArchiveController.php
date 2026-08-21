<?php
/**
 * GenzNewz — Public Archive Controller
 */

class ArchiveController extends Controller {
    public function index(): void {
        $filters = [
            'date' => $_GET['date'] ?? null,
            'month' => $_GET['month'] ?? null,
            'year' => $_GET['year'] ?? null,
            'type_id' => $_GET['edition_type'] ?? null
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $editions = Edition::getArchiveList($filters, $page, 9);
        $editionTypes = EditionType::getActive();

        $this->view('frontend/archive', [
            'pageTitle' => 'সংরক্ষণাগার — পূর্ববর্তী ই-পেপার সংস্করণসমূহ | ' . APP_NAME,
            'editions' => $editions,
            'editionTypes' => $editionTypes,
            'filters' => $filters
        ]);
    }
}

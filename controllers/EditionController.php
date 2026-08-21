<?php
/**
 * GenzNewz — Public ePaper Viewer Controller
 */

declare(strict_types=1);

class EditionController extends Controller {
    public function show(string $slug): void {
        $this->page($slug, 1);
    }

    public function page(string $slug, int|string $pageNumber = 1): void {
        $pageNumber = max(1, (int)$pageNumber);
        $edition = Edition::findWithDetails($slug);

        if (!$edition) {
            $this->error404('অনুরোধ করা ই-পেপার সংস্করণটি খুঁজে পাওয়া যায়নি।');
            return;
        }

        $allPages = EditionPage::getPagesForEdition($edition['id']);
        if (empty($allPages)) {
            // Generate fallback pages if none exist
            $allPages = [];
            for ($i = 1; $i <= 8; $i++) {
                $allPages[] = [
                    'page_number' => $i,
                    'page_title' => "পৃষ্ঠা {$i}",
                    'page_image' => "/storage/pages/original/page_{$i}.svg",
                    'thumbnail' => "/storage/pages/thumb/page_{$i}.svg",
                    'medium_image' => "/storage/pages/medium/page_{$i}.svg"
                ];
            }
        }

        $totalPages = count($allPages);
        if ($pageNumber > $totalPages) {
            $pageNumber = 1;
        }

        // Find current page object
        $currentPage = null;
        foreach ($allPages as $p) {
            if ((int)$p['page_number'] === $pageNumber) {
                $currentPage = $p;
                break;
            }
        }

        if (!$currentPage) {
            $currentPage = $allPages[0];
        }

        // Check if AJAX request for smooth reader transition
        if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $this->json([
                'success' => true,
                'page_number' => $pageNumber,
                'total_pages' => $totalPages,
                'page_title' => $currentPage['page_title'] ?? "পৃষ্ঠা {$pageNumber}",
                'page_image' => $currentPage['page_image'],
                'medium_image' => $currentPage['medium_image'] ?? $currentPage['page_image'],
                'thumbnail' => $currentPage['thumbnail'] ?? $currentPage['page_image'],
                'prev_page' => $pageNumber > 1 ? $pageNumber - 1 : null,
                'next_page' => $pageNumber < $totalPages ? $pageNumber + 1 : null,
                'url' => "/edition/{$slug}/page/{$pageNumber}"
            ]);
            return;
        }

        $otherEditions = Edition::query("SELECT id, title, slug, edition_date, cover_image FROM editions WHERE id != ? AND status = 'published' ORDER BY edition_date DESC LIMIT 4", [$edition['id']]);

        $this->view('frontend/edition', [
            'pageTitle' => "{$edition['title']} (পৃষ্ঠা " . Helper::formatBengaliNumber($pageNumber) . ") | ই-পেপার ভিউয়ার",
            'edition' => $edition,
            'allPages' => $allPages,
            'currentPage' => $currentPage,
            'pageNumber' => $pageNumber,
            'totalPages' => $totalPages,
            'otherEditions' => $otherEditions
        ]);
    }
}

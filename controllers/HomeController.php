<?php
/**
 * GenzNewz — Public Home Controller
 */

class HomeController extends Controller {
    public function index(): void {
        $todayEdition = Edition::getTodayEdition();
        $breakingNews = Article::getBreakingNews(6);
        $featuredStory = Article::getFeaturedStory();
        $topStories = Article::getTopStories(4);
        $latestArticles = Article::getPublished(8);
        $categories = Category::getActive();

        // Group articles by category for homepage blocks
        $categoryNews = [];
        $selectedCatSlugs = ['kolkata', 'state', 'sports', 'business', 'entertainment', 'tech'];
        foreach ($selectedCatSlugs as $slug) {
            $cat = Category::findBySlug($slug);
            if ($cat) {
                $categoryNews[] = [
                    'category' => $cat,
                    'articles' => Article::getPublished(4, $cat['id'])
                ];
            }
        }

        $this->view('frontend/home', [
            'pageTitle' => Helper::getSetting('site_title', APP_TITLE),
            'todayEdition' => $todayEdition,
            'breakingNews' => $breakingNews,
            'featuredStory' => $featuredStory,
            'topStories' => $topStories,
            'latestArticles' => $latestArticles,
            'categories' => $categories,
            'categoryNews' => $categoryNews
        ]);
    }
}

<?php
class HomeController extends Controller {
    public function index() {
        $data = [
            'page_title' => 'GenzNewz — Latest News & ePaper',
            'tagline' => 'Your News. Your Voice.'
        ];
        $this->view('frontend/home', $data);
    }
}
?>
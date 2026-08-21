<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: $view");
        }
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        Helper::redirect($url);
    }
}
?>
<?php 
    class Controller {
        protected function model($model) {
            require_once __DIR__ . "/models/{$model}.php";
            
            return new $model();
        }

        protected function view($view, $data=[]) {
            extract($data);
            require __DIR__ . "/../views/{$view}.php";
        }

        protected function adminView($view, $data=[]) {
            extract($data);
            require __DIR__ . "/../views/admin/admin{$view}.php";

        }
    }

?>
<?php 

    require_once __DIR__ . "/../Controller.php";

    class DashboardController extends Controller {
        public function index() {
            $this->view('dashboard', [
                'title' => 'Dashboard',
            ]);
        }
    }
?>
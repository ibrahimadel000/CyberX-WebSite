<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller {

    public function home() {
        $this->view('public/home');
    }

    public function about() {
        $this->view('public/about');
    }

    public function contact() {
        $this->view('public/contact');
    }

    public function portfolio() {
        $this->view('public/portfolio');
    }

    public function services() {
        $this->view('public/services');
    }

    public function courses() {
        $this->view('public/courses');
    }
}
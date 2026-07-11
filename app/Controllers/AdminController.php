<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller {

    public function index() {
        $this->view('admin/index');
    }

    public function dashboard() {
        $this->view('admin/dashboard');
    }

    public function courses() {
        $this->view('admin/courses');
    }

    public function lessons() {
        $this->view('admin/lessons');
    }

    public function login() {
        $this->view('admin/login');
    }

    public function logout() {
        $this->view('admin/logout');
    }

    public function messages() {
        $this->view('admin/messages');
    }

    public function services() {
        $this->view('admin/services');
    }

    public function solutions() {
        $this->view('admin/solutions');
    }

    public function students() {
        $this->view('admin/students');
    }
}
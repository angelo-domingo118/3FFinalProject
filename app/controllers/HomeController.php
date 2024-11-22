<?php
// app/controllers/HomeController.php

class HomeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        require_once '../app/views/home.php';
    }
} 
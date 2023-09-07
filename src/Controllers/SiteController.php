<?php

namespace App\Controllers;

use App\UseCases\CheckSites;

class SiteController {
    private $checkSites;

    public function __construct(CheckSites $checkSites) {
        $this->checkSites = $checkSites;
    }

    public function monitorSites() {
        $sites = $this->loadSites();
        $this->checkSites->execute($sites);
    }

    private function loadSites(): array {
        return file(__DIR__ . '/../../sites.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    
}
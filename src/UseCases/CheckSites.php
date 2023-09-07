<?php

namespace App\UseCases;

use App\Services\SiteMonitor;
use App\Services\SiteNotifier;

class CheckSites {
    private $siteMonitor;
    private $siteNotifier;

    public function __construct(SiteMonitor $siteMonitor, SiteNotifier $siteNotifier) {
        $this->siteMonitor = $siteMonitor;
        $this->siteNotifier = $siteNotifier;
    }

    public function execute(array $sites) {
        $downSites = $this->siteMonitor->checkSites($sites);
        $this->siteNotifier->notify($downSites);
    }
}

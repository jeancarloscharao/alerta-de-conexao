<?php

require '/var/www/html/vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;
use App\Services\SiteMonitor; 
use App\Services\SiteNotifier;
use App\UseCases\CheckSites;
use App\Gateways\GuzzleHTTPClientGateway;
use App\Gateways\PHPMailerGateway;
use App\Controllers\SiteController;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$httpGateway = new GuzzleHTTPClientGateway(new Client());
$mailGateway = new PHPMailerGateway(new PHPMailer(true)); 

$siteMonitor = new SiteMonitor($httpGateway);
$siteNotifier = new SiteNotifier($mailGateway);

$checkSites = new CheckSites($siteMonitor, $siteNotifier);
$controller = new SiteController($checkSites);

$controller->monitorSites();

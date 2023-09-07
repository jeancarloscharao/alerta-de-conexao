<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SiteMonitor
{
    private $client;
    private $sites;
    private $downSites = [];

    public function __construct(Client $client, array $sites)
    {
        $this->client = $client;
        $this->sites = $sites;
    }

    public function checkSites()
    {
        $requests = $this->createRequests();
        $pool = $this->createPool($requests);
        $promise = $pool->promise();
        $promise->wait();
    }

    private function createRequests()
    {
        return function () {
            foreach ($this->sites as $siteURL) {
                yield new Request('GET', $siteURL, ['timeout' => 5]);
            }
        };
    }

    private function createPool($requests)
    {
        return new Pool($this->client, $requests(), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                if ($response->getStatusCode() !== 200) {
                    $this->downSites[] = $this->sites[$index];
                    $this->logError($this->sites[$index]);
                }
            },
            'rejected' => function ($reason, $index) {
                $this->downSites[] = $this->sites[$index];
                $this->logError($this->sites[$index]);
            },
        ]);
    }

    private function logError($site)
    {   
        date_default_timezone_set('America/Sao_Paulo');
        $logMessage = "[" . date("Y-m-d H:i:s") . "] Site fora do ar: {$site}\n";
        error_log($logMessage, 3, __DIR__ . '/../error.log');
    }

    public function getDownSites()
    {
        return $this->downSites;
    }
}

class SiteNotifier
{
    public function notify(array $downSites)
    {
        if (!empty($downSites)) {
            $this->sendEmail($downSites);
        }
    }

    private function sendEmail(array $downSites)
    {
        $mail = new PHPMailer(true);
        try {
            $this->configureMail($mail);
            $mail->Subject = 'Sites Fora do Ar';
            $mail->Body = 'Os seguintes sites estão fora do ar: ' . implode(", ", $downSites);
            $mail->AltBody = $mail->Body;
            $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        }
    }

    private function configureMail(PHPMailer $mail)
    {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'] ?? '';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'] ?? '';
        $mail->Password = $_ENV['SMTP_PASS'] ?? '';
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'] ?? '';
        $mail->Port = $_ENV['SMTP_PORT'] ?? 0;
        $mail->setFrom($_ENV['MAIL_FROM'] ?? '', $_ENV['MAIL_NAME'] ?? '');
        $mail->addAddress($_ENV['MAIL_TO'] ?? '', $_ENV['MAIL_TO_NAME'] ?? '');
        $mail->isHTML(true);
    }
}

// Carrega variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Lê os sites a partir de um arquivo de texto
$sites = file(__DIR__ . '/../sites.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Inicializa o cliente HTTP
$client = new Client();

// Monitora os sites
$monitor = new SiteMonitor($client, $sites);
$monitor->checkSites();

// Notifica se houver sites fora do ar
$notifier = new SiteNotifier();
$notifier->notify($monitor->getDownSites());


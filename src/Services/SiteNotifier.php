<?php

namespace App\Services;

use App\Gateways\MailGateway;

class SiteNotifier {
    private $mailGateway;

    public function __construct(MailGateway $mailGateway) {
        $this->mailGateway = $mailGateway;
    }

    public function notify(array $downSites) {
        if (!empty($downSites)) {
            $subject = 'Sites Fora do Ar';
            $body = 'Os seguintes sites estão fora do ar: ' . implode(", ", $downSites);
            $this->mailGateway->send($subject, $body);
        }
    }
}

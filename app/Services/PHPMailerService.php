<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    public function __construct(private array $config)
    {
    }

    /**
     * Build a brand-new, fully-configured PHPMailer instance.
     */
    protected function makeMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host       = $this->config['host'] ?? 'smtp.office365.com';
        $mailer->SMTPAuth   = true;
        $mailer->Username   = $this->config['username'];
        $mailer->Password   = $this->config['password'];
        $mailer->SMTPSecure = $this->config['encryption'] ?? 'tls';
        $mailer->Port       = $this->config['port'] ?? 587;
        $mailer->CharSet    = 'UTF-8';

        $mailer->setFrom($this->config['from']['name'] ?? $this->config['username']);
        $mailer->addReplyTo($this->config['replyto']['address'] ?? $this->config['username']);
        
        $mailer->isHTML(true);

        return $mailer;
    }

    /**
     * @param string|array $to       one or more "to" addresses
     * @param string|array $cc       one or more "cc" addresses
     * @param string|array $bcc      one or more "bcc" addresses
     * @param string       $subject
     * @param string       $view     blade view name
     * @param array        $data     data for the view
     */
    public function send(
        string|array $to,
        string|array $cc = [],
        string|array $bcc = [],
        string        $subject = '',
        string        $content = ''
    ): bool {
        // instantiate a brand-new mailer
        $mailer = $this->makeMailer();

        try {
            // add recipients
            foreach ((array) $to as $addr) {
                $mailer->addAddress($addr);
            }
            foreach ((array) $cc as $addr) {
                $mailer->addCC($addr);
            }
            foreach ((array) $bcc as $addr) {
                $mailer->addBCC($addr);
            }

            $mailer->Subject = $subject;

            // render and set bodies
            $mailer->Body    = $content;
            $mailer->AltBody = strip_tags($content);

            return $mailer->send();
        } catch (Exception $e) {
            Log::error("PHPMailer error: {$e->getMessage()}");
            throw $e;
        }
    }
}

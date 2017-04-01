<?php

namespace Phizzl\phpcrontab;


use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;

abstract class AbstractCronStatusHandler
{
    /**
     * @var CronInterface
     */
    protected $cron;

    /**
     * @var CrontabConfig
     */
    protected $config;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * AbstractCronStatusHandler constructor.
     * @param CronInterface $cron
     * @param CrontabConfig $config
     * @param Logger $logger
     */
    public function __construct(CronInterface $cron, CrontabConfig $config, Logger $logger){
        $this->cron = $cron;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param CronInterface $cron
     * @return PHPMailer|null
     */
    protected function createMail(CronInterface $cron){
        $recipients = $this->config->getGlobalReportRecipients();
        if(is_array($cron->getReportRecipients())){
            $recipients = array_merge($recipients, $cron->getReportRecipients());
        }

        if(!count($recipients)){
            $this->logger->addDebug("No recipients for mail", array("cron" => $cron->getName()));
            return null;
        }

        $mailer = new PHPMailer(false);
        $mailer->isHTML(false);
        if($fromAddress = $this->config->getMailFromAddress()){
            $mailer->setFrom($fromAddress, $this->config->getName() . " - " . gethostname());
        }

        if($smtpHost = $this->config->getMailSmtpHost()){
            $mailer->isSMTP();
            $mailer->SMTPAuth = true;
            $mailer->Host = $smtpHost;
        }

        if($mailer->SMTPAuth && ($smtpUser = $this->config->getMailSmtpUser())){
            $mailer->Username = $smtpUser;
        }

        if($mailer->SMTPAuth && ($smtpPass = $this->config->getMailSmtpPass())){
            $mailer->Password = $smtpPass;
        }

        if($mailer->SMTPAuth && ($smtpSecure = $this->config->getMailSmtpSecure())){
            $mailer->SMTPSecure = $smtpSecure;
        }

        if($mailer->SMTPAuth && ($smtpPort = $this->config->getMailSmtpPort())){
            $mailer->Port = $smtpPort;
        }

        $mailer->Subject = "{$this->config->getName()} | Cron: {$cron->getName()}";
        foreach($recipients as $recipient){
            $mailer->addAddress($recipient);
        }

        return $mailer;
    }

    /**
     * @param string $message
     * @return null
     */
    abstract function handle($message = null);
}
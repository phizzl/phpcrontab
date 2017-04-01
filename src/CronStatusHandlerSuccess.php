<?php


namespace Phizzl\phpcrontab;


class CronStatusHandlerSuccess extends AbstractCronStatusHandler
{
    /**
     * @param string|null $message
     * @return null
     */
    public function handle($message = null){
        if($this->cron->getReportStatus() !== CronInterface::REPORT_ALWAYS
            || !($mailer = $this->createMail($this->cron))){
            $this->logger->addDebug("Success mail will not be send", array("cron" => $this->cron->getName(), "reportstatus" => $this->cron->getReportStatus()));
            return null;
        }

        $successMessage = $message === null ? "The cron {$this->cron->getName()} ended successfully!" : $message;
        $mailer->Body = $successMessage;
        if(!$mailer->send()){
            $this->logger->addError("Mail could not be send", array("cron" => $this->cron->getName()));
        }
        else{
            $this->logger->addDebug("Mail was send", array("cron" => $this->cron->getName()));
        }
    }
}
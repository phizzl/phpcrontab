<?php


namespace Phizzl\phpcrontab;


class CronStatusHandlerError extends AbstractCronStatusHandler
{
    /**
     * @param string|null $message
     * @return null
     */
    public function handle($message = null){
        if($this->cron->getReportStatus() !== CronInterface::REPORT_ALWAYS
            || !($mailer = $this->createMail($this->cron))){
            $this->logger->addDebug("Error mail will not be send", array("cron" => $this->cron->getName(), "reportstatus" => $this->cron->getReportStatus()));
            return null;
        }

        $errorMessage = $message === null ? "The cron {$this->cron->getName()} ended with errors!" : $message;
        $mailer->Body = $errorMessage;
        $mailer->Subject .= " | FAILED";
        $mailer->Priority = 1;
        if(!$mailer->send()){
            $this->logger->addError("Mail could not be send", array("cron" => $this->cron->getName()));
        }
        else{
            $this->logger->addDebug("Mail was send", array("cron" => $this->cron->getName()));
        }
    }
}
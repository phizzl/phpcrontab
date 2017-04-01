<?php


namespace Phizzl\phpcrontab;

use Cron\CronExpression;
use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;

class Crontab
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $crons;

    /**
     * @var CrontabConfig
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Crontab constructor.
     */
    public function __construct(){
        $this->name = "PHP Crontab";
        $this->crons = array();
        $this->logger = new Logger($this->name);
    }

    /**
     * @return Logger
     */
    public function getLogger(){
        return $this->logger;
    }

    /**
     * @return CrontabConfig
     */
    public function getConfig(){
        if($this->config === null){
            $this->config = new CrontabConfig();
        }

        return $this->config;
    }

    /**
     * @param CrontabConfig $config
     */
    public function setConfig(CrontabConfig $config){
        $this->config = $config;
    }

    /**
     * @param CronInterface $cron
     */
    public function addCron(CronInterface $cron){
        $this->crons[$cron->getName()] = $cron;
    }

    /**
     * Run the queue
     */
    public function run(){
        $now = new \DateTime();
        /* @var CronInterface $cron */
        foreach($this->crons as $cron){
            $this->getLogger()->addNotice("Starting cron execution", array("cron" => $cron->getName()));

            try{
                $status = $this->runCron($cron, $now);
                if($status === false){
                    $this->getLogger()->addError("The cron ended with errors", array("cron" => $cron->getName()));
                    $this->handleCronError($cron);
                }
                elseif($status === true){
                    $this->getLogger()->addNotice("The cron ended successfully", array("cron" => $cron->getName()));
                    $this->handleCronSuccess($cron);
                }
            }
            catch(\Exception $e){
                $this->getLogger()->addError("The cron ended with errors: " . $e->getMessage(), array("cron" => $cron->getName()));
                $this->getLogger()->addDebug($e->getTraceAsString());
                $this->handleCronError($cron, $e->getMessage());
            }
        }
    }

    /**
     * @param CronInterface $cron
     */
    protected function handleCronError(CronInterface $cron, $errorMessage = null){
        if($cron->getReportStatus() === CronInterface::REPORT_NEVER
            || !($mailer = $this->createMail($cron))){
            return;
        }

        $errorMessage = $errorMessage === null ? "The cron {$cron->getName()} ended with errors!" : $errorMessage;
        $mailer->Body = $errorMessage;
        if(!$mailer->send()){
            $this->getLogger()->addError("Mail could not be send", array("cron" => $cron->getName()));
        }
        else{
            $this->getLogger()->addDebug("Mail was send", array("cron" => $cron->getName()));
        }
    }

    /**
     * @param CronInterface $cron
     */
    protected function handleCronSuccess(CronInterface $cron, $successMessage = null){
        if($cron->getReportStatus() !== CronInterface::REPORT_ALWAYS
            || !($mailer = $this->createMail($cron))){
            return;
        }

        $successMessage = $successMessage === null ? "The cron {$cron->getName()} ended with successfully!" : $successMessage;
        $mailer->Body = $successMessage;
        if(!$mailer->send()){
            $this->getLogger()->addError("Mail could not be send", array("cron" => $cron->getName()));
        }
        else{
            $this->getLogger()->addDebug("Mail was send", array("cron" => $cron->getName()));
        }
    }

    /**
     * @param CronInterface $cron
     * @return PHPMailer|void
     */
    protected function createMail(CronInterface $cron){
        $recipients = array_merge($this->getConfig()->getGlobalReportRecipients(), $cron->getReportRecipients());
        if(!count($recipients)){
            return;
        }

        $mailer = new PHPMailer(false);
        $mailer->isHTML(false);
        if($fromAddress = $this->getConfig()->getMailFromAddress()){
            $mailer->setFrom($fromAddress, $this->name);
        }

        if($smtpHost = $this->getConfig()->getMailSmtpHost()){
            $mailer->isSMTP();
            $mailer->SMTPAuth = true;
            $mailer->Host = $smtpHost;
        }

        if($mailer->SMTPAuth && ($smtpUser = $this->getConfig()->getMailSmtpUser())){
            $mailer->Username = $smtpUser;
        }

        if($mailer->SMTPAuth && ($smtpPass = $this->getConfig()->getMailSmtpPass())){
            $mailer->Password = $smtpPass;
        }

        if($mailer->SMTPAuth && ($smtpSecure = $this->getConfig()->getMailSmtpSecure())){
            $mailer->SMTPSecure = $smtpSecure;
        }

        if($mailer->SMTPAuth && ($smtpPort = $this->getConfig()->getMailSmtpPort())){
            $mailer->Port = $smtpPort;
        }

        $mailer->Subject = "{$this->name} | {$cron->getName()}";
        foreach($recipients as $recipient){
            $mailer->addAddress($recipient);
        }

        return $mailer;
    }

    /**
     * @param CronInterface $cron
     * @param \DateTime $now
     * @return bool|null
     * @throws \Exception
     */
    protected function runCron(CronInterface $cron, \DateTime $now){
        $cronExpression = $this->createCronExpression($cron);

        if(!$cronExpression->isDue($now)
            || $this->isCronLocked($cron)){
            $this->getLogger()->addDebug("Cron is not due or locked!", array("cron" => $cron->getName()));
            return null;
        }

        $this->getLogger()->addDebug("Cron will be executed", array("cron" => $cron->getName()));
        $status = true;
        $this->lockCron($cron);
        try{
            if(($t = $cron->execute()) !== null){
                $status = $t;
            }
        }
        catch(\Exception $e){
            $this->unlockCron($cron);
            throw $e;
        }

        $this->unlockCron($cron);
        return $status;
    }

    /**
     * @param CronInterface $cron
     * @return CronExpression
     */
    protected function createCronExpression(CronInterface $cron){
        $cronExpressionString = implode(" ", array(
            $cron->getMinute(),
            $cron->getHour(),
            $cron->getDay(),
            $cron->getMonth(),
            $cron->getDayOfWeek()
        ));

        return CronExpression::factory($cronExpressionString);
    }

    /**
     * @param CronInterface $cron
     * @return bool
     */
    public function isCronLocked(CronInterface $cron){
        return file_exists($this->getCronLockFilePath($cron));
    }

    /**
     * @param CronInterface $cron
     * @return bool
     * @throws CrontabException
     */
    public function lockCron(CronInterface $cron){
        $cronLockFilePath = $this->getCronLockFilePath($cron);
        if(!touch($cronLockFilePath)){
            throw new CrontabException("Cannot write cronlock: {$cronLockFilePath}");
        }

        return true;
    }

    /**
     * @param CronInterface $cron
     * @return bool
     * @throws CrontabException
     */
    public function unlockCron(CronInterface $cron){
        $cronLockFilePath = $this->getCronLockFilePath($cron);
        if(!unlink($cronLockFilePath)){
            throw new CrontabException("Cannot remove cronlock: {$cronLockFilePath}");
        }

        return true;
    }

    /**
     * @param CronInterface $cron
     * @return string
     */
    protected function getCronLockFilePath(CronInterface $cron){
        $cronIdent = $this->cleanStringFromSpecialChars($cron->getName());
        return $this->getConfig()->getCronLockDir() . DIRECTORY_SEPARATOR . "{$cronIdent}.cron_lock";
    }

    /**
     * @param $string
     * @return string
     */
    protected function cleanStringFromSpecialChars($string){
        $string = str_replace(' ', '-', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        return preg_replace('/-+/', '-', $string);
    }
}
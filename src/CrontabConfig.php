<?php


namespace Phizzl\phpcrontab;


class CrontabConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cronLockDir;

    /**
     * @var string
     */
    private $mailFromAddress;

    /**
     * @var string
     */
    private $mailSmtpHost;

    /**
     * @var string
     */
    private $mailSmtpUser;

    /**
     * @var string
     */
    private $mailSmtpPass;

    /**
     * @var string
     */
    private $mailSmtpSecure;

    /**
     * @var int
     */
    private $mailSmtpPort;

    /**
     * @var array
     */
    private $globalReportRecipients;

    /**
     * CrontabConfig constructor.
     */
    public function __construct(){
        $this->name = "PHP Crontab";
        $this->cronLockDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'locks';
        $this->globalReportRecipients = array();

        $this->mailFromAddress = "phpcrontab@" . gethostname();
        $this->mailSmtpSecure = 'tls';
        $this->mailSmtpPort = 587;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCronLockDir(){
        return $this->cronLockDir;
    }

    /**
     * @param string $cronLockDir
     */
    public function setCronLockDir($cronLockDir){
        $this->cronLockDir = $cronLockDir;
    }

    /**
     * @return string
     */
    public function getMailFromAddress(){
        return $this->mailFromAddress;
    }

    /**
     * @param string $mailFromAddress
     */
    public function setMailFromAddress($mailFromAddress){
        $this->mailFromAddress = $mailFromAddress;
    }

    /**
     * @return array
     */
    public function getGlobalReportRecipients(){
        return $this->globalReportRecipients;
    }

    /**
     * @return string
     */
    public function getMailSmtpHost(){
        return $this->mailSmtpHost;
    }

    /**
     * @param string $mailSmtpHost
     */
    public function setMailSmtpHost($mailSmtpHost){
        $this->mailSmtpHost = $mailSmtpHost;
    }

    /**
     * @return string
     */
    public function getMailSmtpUser(){
        return $this->mailSmtpUser;
    }

    /**
     * @param string $mailSmtpUser
     */
    public function setMailSmtpUser($mailSmtpUser){
        $this->mailSmtpUser = $mailSmtpUser;
    }

    /**
     * @return string
     */
    public function getMailSmtpPass(){
        return $this->mailSmtpPass;
    }

    /**
     * @param string $mailSmtpPass
     */
    public function setMailSmtpPass($mailSmtpPass){
        $this->mailSmtpPass = $mailSmtpPass;
    }

    /**
     * @return string
     */
    public function getMailSmtpSecure(){
        return $this->mailSmtpSecure;
    }

    /**
     * @param string $mailSmtpSecure
     */
    public function setMailSmtpSecure($mailSmtpSecure){
        $this->mailSmtpSecure = $mailSmtpSecure;
    }

    /**
     * @return int
     */
    public function getMailSmtpPort(){
        return $this->mailSmtpPort;
    }

    /**
     * @param int $mailSmtpPort
     */
    public function setMailSmtpPort($mailSmtpPort){
        $this->mailSmtpPort = $mailSmtpPort;
    }

    /**
     * @param array $globalReportRecipients
     */
    public function setGlobalReportRecipients($globalReportRecipients){
        $this->globalReportRecipients = $globalReportRecipients;
    }
}
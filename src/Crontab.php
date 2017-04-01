<?php


namespace Phizzl\phpcrontab;

use Cron\CronExpression;

class Crontab
{

    /**
     * @var array
     */
    private $crons;

    /**
     * @var CrontabConfig
     */
    private $config;

    /**
     * Crontab constructor.
     */
    public function __construct(){
        $this->crons = array();
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
     *
     */
    public function run(){
        $now = new \DateTime();
        foreach($this->crons as $cron){
            $this->runCron($cron, $now);
        }
    }

    /**
     * @param CronInterface $cron
     * @param \DateTime $now
     * @return bool
     */
    protected function runCron(CronInterface $cron, \DateTime $now){
        $cronExpression = $this->createCronExpression($cron);

        if(!$cronExpression->isDue($now)
            || $this->isCronLocked($cron)){
            return null;
        }

        $status = true;
        $this->lockCron($cron);
        try{
            $cron->execute();
        }
        catch(\Exception $e){
            $status = false;
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
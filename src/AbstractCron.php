<?php


namespace Phizzl\phpcrontab;


abstract class AbstractCron implements CronInterface
{
    /**
     * @var int|string
     */
    protected $minute;

    /**
     * @var int|string
     */
    protected $hour;

    /**
     * @var int|string
     */
    protected $day;

    /**
     * @var int|string
     */
    protected $month;

    /**
     * @var int|string
     */
    protected $dayOfWeek;

    /**
     * @var array
     */
    protected $reportRecipients;

    /**
     * @var int
     */
    protected $reportStatus;

    /**
     * @return int|string
     */
    public function getMinute(){
        return $this->minute;
    }

    /**
     * @param int|string $minute
     */
    public function setMinute($minute){
        $this->minute = $minute;
    }

    /**
     * @return int|string
     */
    public function getHour(){
        return $this->hour;
    }

    /**
     * @param int|string $hour
     */
    public function setHour($hour){
        $this->hour = $hour;
    }

    /**
     * @return int|string
     */
    public function getDay(){
        return $this->day;
    }

    /**
     * @param int|string $day
     */
    public function setDay($day){
        $this->day = $day;
    }

    /**
     * @return int|string
     */
    public function getMonth(){
        return $this->month;
    }

    /**
     * @param int|string $month
     */
    public function setMonth($month){
        $this->month = $month;
    }

    /**
     * @return int|string
     */
    public function getDayOfWeek(){
        return $this->dayOfWeek;
    }

    /**
     * @param int|string $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek){
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @param int $reportStatus
     */
    public function setReportStatus($reportStatus){
        $this->reportStatus;
    }

    /**
     * @return int
     */
    public function getReportStatus(){
        return $this->reportStatus;
    }

    /**
     * @param array $reportRecipients
     */
    public function setReportRecipients(array $reportRecipients){
        $this->reportRecipients = $reportRecipients;
    }

    /**
     * @return array
     */
    public function getReportRecipients(){
        return $this->reportRecipients;
    }


    /**
     * @inheritdoc
     */
    abstract public function getName();

    /**
     * @inheritdoc
     */
    abstract public function execute();
}
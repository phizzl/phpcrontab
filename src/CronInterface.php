<?php

namespace Phizzl\phpcrontab;


interface CronInterface
{
    const REPORT_ALWAYS = 2;

    const REPORT_ON_ERROR = 1;

    const REPORT_NEVER = 0;

    /**
     * @param int|string $minute
     */
    public function setMinute($minute);

    /**
     * @param int|string $minute
     */
    public function setHour($hour);

    /**
     * @param int|string $minute
     */
    public function setDay($day);

    /**
     * @param int|string $minute
     */
    public function setMonth($month);

    /**
     * @param int|string $minute
     */
    public function setDayOfWeek($dayOfWeek);

    /**
     * @return int|string
     */
    public function getMinute();

    /**
     * @return int|string
     */
    public function getHour();

    /**
     * @return int|string
     */
    public function getDay();

    /**
     * @return int|string
     */
    public function getMonth();

    /**
     * @return int|string
     */
    public function getDayOfWeek();

    /**
     * @param int $reportStatus
     */
    public function setReportStatus($reportStatus);

    /**
     * @return int
     */
    public function getReportStatus();

    /**
     * @return array
     */
    public function getReportRecipients();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function execute();
}
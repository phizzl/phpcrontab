<?php

namespace Phizzl\phpcrontab;


interface CronInterface
{
    public function setMinute($minute);

    public function setHour($hour);

    public function setDay($day);

    public function setMonth($month);

    public function setDayOfWeek($dayOfWeek);

    public function getMinute();

    public function getHour();

    public function getDay();

    public function getMonth();

    public function getDayOfWeek();

    public function getName();

    public function execute();
}
<?php


namespace Phizzl\phpcrontab;


class SimpleCron extends AbstractCron
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $executable;

    /**
     * SimpleCron constructor.
     * @param string $name
     * @param callable $executable
     * @param string|null $schedule
     * @throws CrontabException
     */
    public function __construct($name, $executable, $schedule = null){
        $this->name = $name;

        $this->executable = $executable;
        if(!is_callable($executable)){
            throw new CrontabException("Uncallabe");
        }

        $schedule = $schedule === null ? "* * * * *" : $schedule;
        $scheduleParts = explode(" ", $schedule);

        $this->setMinute($scheduleParts[0]);
        $this->setHour($scheduleParts[1]);
        $this->setDay($scheduleParts[2]);
        $this->setMonth($scheduleParts[3]);
        $this->setDayOfWeek($scheduleParts[4]);
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function execute(){
        $executable = $this->executable;
        return $executable();
    }

}
<?php


namespace Phizzl\phpcrontab;


class CrontabConfig
{
    /**
     * @var string
     */
    private $cronLockDir;

    /**
     * CrontabConfig constructor.
     */
    public function __construct(){
        $this->cronLockDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'locks';
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
}
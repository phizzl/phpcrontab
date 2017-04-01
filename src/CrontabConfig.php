<?php


namespace Phizzl\phpcrontab;


class CrontabConfig
{
    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var string
     */
    private $phpIniFile;

    /**
     * @var array
     */
    private $phpExecutionFlags;

    /**
     * @var string
     */
    private $cronLockDir;

    /**
     * CrontabConfig constructor.
     */
    public function __construct(){
        $this->phpBin = "php";
        $this->phpIniFile = "";
        $this->phpExecutionFlags = array();
        $this->cronLockDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'locks';
    }

    /**
     * @return string
     */
    public function getPhpBin(){
        return $this->phpBin;
    }

    /**
     * @param string $phpBin
     */
    public function setPhpBin($phpBin){
        $this->phpBin = $phpBin;
    }

    /**
     * @return string
     */
    public function getPhpIniFile(){
        return $this->phpIniFile;
    }

    /**
     * @param string $phpIniFile
     */
    public function setPhpIniFile($phpIniFile){
        $this->phpIniFile = $phpIniFile;
    }

    /**
     * @return array
     */
    public function getPhpExecutionFlags(){
        return $this->phpExecutionFlags;
    }

    /**
     * @param array $phpExecutionFlags
     */
    public function setPhpExecutionFlags(array $phpExecutionFlags){
        $this->phpExecutionFlags = $phpExecutionFlags;
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
<?php

namespace App\Classes;


class Config
{
    protected $path;
    protected $file;
    protected $intAngle;
    protected $locationX = 0;
    protected $locationY = 0;
    protected $useConsoleInput = true;

    public function __construct()
    {
        $this->path = "test/";
        $this->file = "input.txt";
        $this->intAngle = 0;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getIntAngle()
    {
        return $this->intAngle;
    }

    /**
     * @param int $intAngle
     */
    public function setIntAngle($intAngle)
    {

        //-- Check if specified angle is more than circle
        if (abs($intAngle) >= 360) {
            $intAngle = $intAngle % 360;
        }

        //-- Check if provided angle is negative value
        if ($intAngle < 0) {
            $intAngle = 360 - abs($intAngle);
        }

        if(!empty($intAngle)){
            $this->intAngle += $intAngle;
        }else{
            $this->intAngle = $intAngle;
        }
        $this->intAngle %= 360;
    }

    /**
     * @return int
     */
    public function getLocationY()
    {
        return $this->locationY;
    }

    /**
     * @param int $locationY
     * @param string $strAction
     */
    public function setLocationY($locationY, $strAction = "")
    {
        if(empty($strAction)){
            $this->locationY = $locationY;
        }else{
            $intAngle = $this->getIntAngle();
            if (0 <= (float)$intAngle && (float)$intAngle <= 180) {
                $this->locationY += abs($locationY);
            } else {
                $this->locationY -= abs($locationY);
            }
        }

    }

    /**
     * @return int
     */
    public function getLocationX()
    {
        return $this->locationX;
    }

    /**
     * @param int $locationX
     * @param string $strAction
     */
    public function setLocationX($locationX, $strAction = "")
    {
        if(empty($strAction)){
            $this->locationX = $locationX;
        }else{
            $intAngle = $this->getIntAngle();
            if ((0 <= (float)$intAngle && (float)$intAngle <= 90) || (270 <= (float)$intAngle && (float)$intAngle <= 360)) {
                $this->locationX += abs($locationX);
            } else {
                $this->locationX -= abs($locationX);
            }
        }


    }

    /**
     * @return bool
     */
    public function isUseConsoleInput()
    {
        return $this->useConsoleInput;
    }

    /**
     * @param bool $useConsoleInput
     */
    public function setUseConsoleInput($useConsoleInput)
    {
        $this->useConsoleInput = $useConsoleInput;
    }


}
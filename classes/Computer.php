<?php

namespace App\Classes;

class Computer
{

    private $config;
    private $intPersons = 0;
    private $arrDestinations = [];
    private $arrResult= [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function compute(Input $input)
    {

        $arrData=($this->config->isUseConsoleInput())?$input->readInput():$input->readInputFile($this->config->getPath() . $this->config->getFile());

        foreach ($arrData as $line) {
            $this->validate($line);

            if ($this->getIntPersons() <= 0) {
                //-- Show result for current people
                $this->getResult();
                //-- Reset array of destinations
                $this->arrDestinations = [];
            }

            if ($line == 0) {
                $this->showResult();
                break;
            }
        }
    }

    public function calculateAverageDestination()
    {
        $arrResult = [];
        if (!empty($this->arrDestinations)) {
            $arrX = [];
            $arrY = [];
            foreach ($this->arrDestinations as $destination) {
                $arrX[] = $destination['x'];
                $arrY[] = $destination['y'];
            }
            $arrResult['x'] = array_sum($arrX) / count($this->arrDestinations);
            $arrResult['y'] = array_sum($arrY) / count($this->arrDestinations);
        }
        return $arrResult;

    }

    public function calculateWorseDistanceFromAverage($arrAverageDestination)
    {
        $arrResult = [];
        if (!empty($this->arrDestinations)) {

            foreach ($this->arrDestinations as $destination) {
                $arrResult[]=sqrt(pow(abs($arrAverageDestination['x']-$destination['x']),2)+pow(abs($arrAverageDestination['y']-$destination['y']),2));
            }
        }

        return !empty($arrResult)?max($arrResult):null;

    }

    public function getResult()
    {
        //-- Get average destination
        $arrDestination=$this->calculateAverageDestination();
        $intWorseDistance=$this->calculateWorseDistanceFromAverage($this->calculateAverageDestination());
        if (!empty($arrDestination)) $this->arrResult[]= round($arrDestination['x'],4) . " " . round($arrDestination['y'],4) . " " . round($intWorseDistance,5) . "\n";
    }

    public function showResult()
    {
        foreach ($this->arrResult as $result){
            echo $result;
        }
    }

    public function validate($line)
    {
        //-- Detect initial coordinates
        preg_match('/^[-+]?[\d.\s]+/', $line, $matches);
        if (!empty($matches)) {
            $arrTemp = explode(" ", trim($matches[0]));
            if (count($arrTemp) <= 1) {

                //-- Set quontity of people that were asked for direction
                $this->setIntPersons(implode(",", $arrTemp));
                //echo "Persons=".$this->getIntPersons()."\n";
            } else {
                $this->config->setLocationX($arrTemp[0]);
                $this->config->setLocationY($arrTemp[1]);
            }
        }

        //-- Detect initial initial angle
        preg_match('/[start]+\s[+-]?[\w.]+/', $line, $matches);
        if (!empty($matches)) {
            $intAngle = str_replace("start ", "", trim($matches[0]));
            $this->config->setIntAngle($intAngle);
        }

        //-- Detect walk and turn actions and perform it
        preg_match_all('/\b[walkturn]+\s[+-]?[\w.]+/', $line, $matches);
        if (!empty($matches)) {
            $this->performActions($matches[0]);
        }

        //-- Fill in array with coordinates for directions lines
        preg_match('/\w+\s+\w+/', $line, $matches);
        if (!empty($matches)) {
            $this->arrDestinations[$this->getIntPersons()]['x'] = $this->config->getLocationX();
            $this->arrDestinations[$this->getIntPersons()]['y'] = $this->config->getLocationY();
            $this->setIntPersons($this->getIntPersons() - 1);
            $this->config->setIntAngle(0);
        }

    }

    public function performActions($arrActions)
    {
        foreach ($arrActions as $action) {
            $arrActionData = explode(" ", $action);
            switch ($arrActionData[0]) {
                case "walk":
                    $this->walk($arrActionData[1]);
                    break;
                case "turn":
                    $this->config->setIntAngle($arrActionData[1]);
                    break;
            }
        }

    }


    public function walk($points)
    {
        //-- Calculate cos & sin for direction
        $arrMath['cos'] = cos(deg2rad($this->config->getIntAngle()));
        $arrMath['cos'] = (abs($arrMath['cos']) < 1e-10) ? 0 : $arrMath['cos'];
        $arrMath['sin'] = sqrt(1 - $arrMath['cos'] * $arrMath['cos']);

        //-- Change current position
        $pointsX = $points * $arrMath['cos'];
        //echo  "Current angle=". $this->config->getIntAngle()."\n";
        //echo  "Current X=". $this->config->getLocationX()."\n";
        //echo  "Current Y=".$this->config->getLocationY()."\n";
        //echo  "cos=".$arrMath['cos']."\n";
        //echo "GO by X=".$pointsX."\n";
        $this->config->setLocationX($pointsX, 'walk');
        $pointsY = $points * $arrMath['sin'];
        //echo  "sin=".$arrMath['sin']."\n";
        //echo "Go by Y=".$pointsY."\n";
        //echo "=============\n";
        $this->config->setLocationY($pointsY, 'walk');
    }


    /**
     * @return int
     */
    public function getIntPersons()
    {
        return $this->intPersons;
    }

    /**
     * @param int $intPersons
     */
    public function setIntPersons($intPersons)
    {
        $this->intPersons = $intPersons;
    }

}
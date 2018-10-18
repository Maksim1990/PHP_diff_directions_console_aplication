<?php

namespace App\Classes;


class Input
{
    /**
     * Read input from test file
     * @param $fullPath
     * @return \Generator
     */
    public function readInputFile($fullPath)
    {
        $handle = fopen($fullPath, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                yield  $line . "\n";
            }
            fclose($handle);
        } else {
            // error opening the file.
        }
    }


    /**
     * Read input from console command
     * @return \Generator
     */
    public function readInput()
    {
        do {
            $line = readline("Command: ");
            yield  $line;
        } while ($line != 0);
    }

}
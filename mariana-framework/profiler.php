<?php
/**
 * User: filipe_2
 * Date: 2/7/2016
 * Time: 3:45 PM
 */

namespace Mariana\Framework;

/**
 * Class profiler
 * @package Mariana\Framework
 * @desc class for profiling our applications without extensions like xdebug or hrproof
 */

class Profile {
    /**
     * Stores details about the last profiled method
     */
    private $details;
    private $path_to_class;
    private $classname;
    private $methodname;
    private $methodargs;
    private $loops;
    private $memory = array();
    private $reflection;

    public function profile($classname, $methodname, $methodargs, $invocations = 1) {
        if(class_exists($classname) != TRUE) {
            throw new Exception("{$classname} doesn't exist");
        }

        $method = new ReflectionMethod($classname, $methodname);

        $this->reflection = $method;

        $instance = NULL;
        if(!$method->isStatic())         {
            $class = new ReflectionClass($classname);
            $instance = $class->newInstance();
        }
        
        $durations = array();
        for($i = 0; $i < $invocations; $i++) {
            $start = microtime(true);
            $method->invokeArgs($instance, $methodargs);
            $durations[] = microtime(true) - $start;
            $memory_script[] = (memory_get_peak_usage(false)/1024/1024);  // User from the script
            $memory_real[] = (memory_get_peak_usage(true)/1024/1024);   // Allocated memory from the system
        }

        $duration["total"] = round(array_sum($duration), 4);
        $duration["average"] = round($duration["total"] / count($durations), 4);
        $duration["worst"] = round(max($durations), 4);

        $mm_real["total"] = round(array_sum($memory_real), 4);
        $mm_real["average"] = round($mm_real["total"] / count($memory_real), 4);
        $mm_real["worst"] = round(max($memory_real), 4);

        $mm_script["total"] = round(array_sum($memory_script), 4);
        $mm_script["average"] = round($mm_script["total"] / count($memory_script), 4);
        $mm_script["worst"] = round(max($memory_script), 4);

        $this->details = array( "date"  => date('Y-m-d h:m:s'),
                                "class" => $classname,
                                "method" => $methodname,
                                "arguments" => $methodargs,
                                "duration" => $duration,
                                "invocations" => $invocations,
                                "allocated_memory_average" => $mm_real['average'],
                                "allocated_memory_worst" => $mm_real['worst'],
                                "script_memory_average" => $mm_script['average'],
                                "script_memory_worst" => $mm_script['worst']);

        $this->printDetails();
        $this->storeDetails();
    }


    private function storeDetails(){
        $fh = fopen($this->reflection->getFileName(), 'a');
        $fw = fwrite($fh,json_encode($this->details));
        $fc = fclose($fh);
    }

    private function invokedMethod() {
        return "{$this->details["class"]}::{$this->details["method"]}(" .
        join(", ", $this->details["arguments"]) . ")"; 
    }

    public function printDetails() {
        $methodString = $this->invokedMethod();
        $numInvoked = $this->details["invocations"];
        
        if($numInvoked == 1) {
            echo "{$methodString} took {$this->details["duration"]["average"]}s\n";
        }

        else {
            echo "{$methodString} was invoked {$numInvoked} times\n";
            echo "Total duration:   {$this->details["duration"]["total"]}s\n";
            echo "Average duration: {$this->details["duration"]["average"]}s\n";
            echo "Worst duration:   {$this->details["duration"]["worst"]}s\n";
        }
    }
}
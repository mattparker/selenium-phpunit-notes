<?php
/**
 *
 * Copyright (c) 2014 Matt Parker.
 * All rights reserved.
 *
 * @author     Matt Parker <matt@lamplightdb.co.uk>
 * @copyright  2014 Matt Parker <matt@lamplightdb.co.uk>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 *
 */



/**
 * This is just a code sample to accompany the Nomad php lightning talk on
 * Selenium and phpunit.
 *
 * Not my idea (obviously, check sausage library, I think) but slightly different implementation.
 * Useful when you've got ajax-ey stuff that you're testing responses of and don't know how
 * long they'll take.  More elegant than sleep(5)!
 *
 */

class MySeleniumBaseClass extends PHPUnit_Extensions_Selenium2TestCase {

    /**
     *
     * Try some assertions until they pass (or the timeout)
     *
     * @param string $message Message to tell you what happened
     * @param callable $assertFunction A function that contains some assertions we want to be satisfied
     * @param array $arguments Arguments to pass to the function
     * @param int $numAttempts Number of attempts to make
     * @param float $frequencyToCheck How frequently (in seconds) to check
     *
     * @throws PHPUnit_Framework_ExpectationFailedException
     * @throws Exception
     */
    public function spinAssert ($message, callable $assertFunction, $arguments = [], $numAttempts = 5, $frequencyToCheck = 1) {

        $num_tries = 0;
        $result = false;
        $assertFunctionException = false;
        $anyOtherException = false;

        if (!($frequencyToCheck > 0)) {
            $frequencyToCheck  = 1;
        }

        $sleepPeriod = $frequencyToCheck * 1000000;


        while ($num_tries < $numAttempts && !$result) {

            $result = true;
            $assertFunctionException = null;
            $anyOtherException = null;

            try {

                call_user_func_array($assertFunction, $arguments);

            } catch (PHPUnit_Framework_ExpectationFailedException $assertFunctionException) {

                $result = false;

            } catch (\Exception $anyOtherException) {

                $result = false;

            }

            if (!$result) {

                usleep($sleepPeriod);

            }

            $num_tries++;
        }

        // re-throw the Exception we caught because it's still valid,
        // we tried and tried and it just wouldn't come good
        if ($result === false && $assertFunctionException) {
            throw $assertFunctionException;
        }

        // Hmm, there was something else then...
        if ($result === false && $anyOtherException) {
            throw $anyOtherException;
        }

    }


}

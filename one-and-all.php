<?php
/**
 *
 * Copyright (c) 2014 Matt Parker.
 * All rights reserved.
 *
 * @author     Matt Parker <matt@lamplightdb.co.uk>
 * @copyright  2014 Matt Parker <matt@lamplightdb.co.uk>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */



/**
 * This is just a code sample to accompany the Nomad php lightning talk on
 * Selenium and phpunit.
 *
 * I add these two methods to my first extension class (which is then extended
 * with the actual tests).  They are simple wrappers for the slightly more verbose
 * API to find a single, or multple elements, using a CSS selector.
 *
 */

class MySeleniumBaseClass extends PHPUnit_Extensions_Selenium2TestCase {


    /**
     * Finds a single element matching the CSS selector
     *
     * @param  string $cssSelector CSS selector to query the DOM for
     * @return PHPUnit_Extensions_Selenium2TestCase_Element | PHPUnit_Extensions_Selenium2TestCase_Element_Select
     */
    public function one ($cssSelector) {
        $el = $this->byCss($cssSelector);
        return $this->_wrapSelectElement($el);
    }


    /**
     *
     * Finds all element matching the CSS selector
     * @param  string $cssSelector CSS selector to query the DOM for
     * @return array               Elements found.
     */
    public function all ($cssSelector) {

        $els = $this->elements(
            $this->using('css selector')->value($cssSelector)
        );
        $ret = array();

        foreach ($els as $el) {
            $ret[] = $this->_wrapSelectElement($el);
        }
        return $ret;
    }


    /**
     * Checks if element is a select and calls $this->select
     * on it if so.
     *
     * @param  PHPUnit_Extensions_Selenium2TestCase_Element $el
     * @return PHPUnit_Extensions_Selenium2TestCase_Element | PHPUnit_Extensions_Selenium2TestCase_Element_Select
     */
    private function _wrapSelectElement ($el) {
        if ($el->name() === "select") {
            return $this->select($el);
        }
        return $el;
    }
}

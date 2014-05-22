<?php
/**
 * Helper to make selecting lots of things in forms a bit easier
 *
 */

trait Helpers_FormSetter {




    /**
     * Sets the values of a bunch of form elements.
     *
     * In the array passed:
     *  - the key in each should be a CSS selector of the element.
     *  - the value should be the value(s) for that element
     *
     * If the element is a select-multiple, you can pass an array and they'll
     * all be selected.  It uses selectOptionByLabel, so pass label values.
     *
     * If the element is a multi checkbox or radio, the array of values passed
     * should be CSS selectors for the checkboxes that should be selected.
     *
     * e.g.:
     *
     * $this->setFormValues(array(
     *     '#myTextElement' => 'Hello there matey',
     *     '#mySelectBox'   => 'This is the label of an option',
     *     '#aMultiSelect'  => array('Label num 1', 'Another label to select'),
     *     '#someChecks'    => array('#checkbox3-1', '#checkbox3-2') // hmm CSS
     * ));
     *
     * Any previously selected values won't be changed, nothing is reset etc.
     *
     *
     * @param $cssToValues array  Keys are css selectors, values are form values to set
     *
     */
    public function setFormValues (array $cssToValues) {

        foreach ($cssToValues as $cssSelector => $formValue) {

            $element = $this->one($cssSelector);
            $name = $element->name();
            $type = $element->attribute("type");
            $elementClass = $element->attribute('class');

            if ($name == 'select') {

                $this->helpersFormSetter_setOptionValues($element, (array)$formValue);

            } else if ($type == 'radio' || $type == 'checkbox') {

                $this->helpersFormSetter_setCheckboxValues($element, (array)$formValue);

            } else {

                $element->value($formValue);

            }

        }

    }

    /**
     * Check radio or multi checkbox.  Values here are css selectors for each
     * radio/checkbox that should be selected.
     *
     * @param $element PHPUnit_Extensions_Selenium2TestCase_Element
     * @param $values array
     */
    private function helpersFormSetter_setCheckboxValues (PHPUnit_Extensions_Selenium2TestCase_Element $element, array $values) {

        foreach ($values as $value) {
            $check = $this->one($value);
            if ($check->selected() === false) {
                $check->click();
            }
        }
    }


    /**
     * Set multiple options on a multi-style element
     * @param $element
     * @param $values array
     */
    private function helpersFormSetter_setOptionValues (PHPUnit_Extensions_Selenium2TestCase_Element_Select $element, array $values) {

        foreach ($values as $oneValue) {
            $element->selectOptionByLabel($oneValue);
        }

    }




}



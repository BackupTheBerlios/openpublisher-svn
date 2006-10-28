<?php

class TestEditController extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;

    public function perform()
    {
        //die($module_controller);
        // execute the requested module controller and assign template variable
        // with the result.
        $this->viewVar['mod_test_edit_message'] = 'This messages comes from the "test" module "edit" controller';                      
    }
}

?>
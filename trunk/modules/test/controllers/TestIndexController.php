<?php

class TestIndexController extends JapaControllerAbstractPage
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
        $this->viewVar['mod_test_message'] = 'This messages comes from the "test" module "index" controller';                      
    }
}

?>
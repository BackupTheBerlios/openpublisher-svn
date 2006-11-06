<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >


// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Main controller of the default module
 * 
 */
class DefaultIndexController extends JapaControllerAbstractPage
{


    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * main controller methode
     */
    public function perform()
    {
        // assign default template variable
        $this->viewVar['default_message'] = 'Hi! This message comes from the "default" module "index" controller.';    
        
        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        //$this->viewVar['module_header_controller'] = $this->controllerLoader->DefaultHeader();    
        
        $this->viewVar['what_would_you_do'] = array(); 
        //$this->controllerLoader->broadcast( $this->viewVar['what_would_you_do'], 'whatWouldYouDo' );            
    }
}

?>
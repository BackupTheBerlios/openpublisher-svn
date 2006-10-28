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
    }
}

?>
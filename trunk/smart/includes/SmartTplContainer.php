<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smart template container class
 *
 *
 */
 
class SmartTplContainer extends SmartContainer
{
    /**
     * Global config variables
     *
     * @var mixed $config
     */
    public $config = NULL;
    
    /**
     * Template variables
     *
     * @var mixed $vars
     */
    public $vars = NULL;

    /**
     * View variables
     *
     * @var mixed $vars
     */
    public $viewVar = NULL;
    
    /**
     * View loader object
     *
     * @var object $viewLoader
     */
    public $viewLoader;    

    /**
     * Template buffer content
     *
     * @var string tplBufferContent
     */
    public $tplBufferContent = '';    
    
    /**
     * render the template
     *
     */
    function renderTemplate()
    {
    } 
}

?>
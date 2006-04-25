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
 * Parent class of every ajax view class
 *
 */

class SmartAjaxView extends SmartObject
{
    /**
     * The model object
     * @var object $model
     */
    public $model;

    /**
     * The session object
     * @var object $session
     */
    public $session;
    
     /**
     * Smart main configuration array
     * @var array $config
     */
    public $config;   

     /**
     * Registered Ajax methods
     * @var array $methods
     */
    public $methods = array();

    /**
     * constructor
     *
     */
    public function __construct()
    {
    }
    
    /**
     * authentication
     *
     */
    public function auth()
    {
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
    }     
}

?>

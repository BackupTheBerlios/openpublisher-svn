<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >2004, 2005, 2006
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * class JapaControllerRpcAbstractPage
 *
 */

class JapaControllerRpcAbstractPage
{

    /**
     * Controller variable container
     * @var mixed $controllerVar
     */
    public $controllerVar = false;

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
     * Japa main configuration array
     * @var array $config
     */
    public $config;   

     /**
     * Default error view
     * @var string $errorController
     */
    public $errorController = 'error';

     /**
     * Data container passed to the controller
     * @var mixed $controllerData
     */
    public $controllerData = false;  

    /**
     * authentication
     *
     */
    public function auth(){}

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain(){}
}

?>

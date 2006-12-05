<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >2004, 2005, 2006
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * class JapaPageController
 *
 */

abstract class JapaControllerAbstractPage
{
    /**
     * View variable container
     * @var mixed $viewVar
     */
    public $viewVar = false;

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
     * Japa main configuration object
     * @var object $config
     */
    public $config;   

     /**
     * Default error view
     * @var string $errorController
     */
    public $errorController = 'error';

     /**
     * View engine
     * @var string $viewEngine
     */
    public $viewEngine = false;

     /**
     * View related to this controller if any
     * @var string $view
     */
    public $view = '';

     /**
     * View render flag
     * @var bool $renderView
     */
    public $renderView = true;

     /**
     * View folder
     * @var bool $viewFolder
     */
    public $viewFolder = false;
    
     /**
     * the controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = false;

     /**
     * Data container passed to the controller
     * @var mixed $controllerData
     */
    public $controllerData = false;

     /**
     * Cache expire time of a request
     * @var int $cacheExpire 0 = cache disabled
     */
    public $cacheExpire = 0;
    
     /**
     * Cache ID of a request
     * @var string $cacheId
     */
    public $cacheId = false;    

    /**
     * constructor
     *
     */
    public function __construct( $data ){}

    /**
     * perform
     *
     */
    abstract public function perform();

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

    /**
     * append filter chain
     *
     */
    public function appendFilterChain( & $viewBufferContent ){}
}

?>

<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >


// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * Web Admin controller class
 *
 */
class JapaControllerModuleApplication extends JapaController
{
    /**
     * View factory object
     *
     * @var object $view
     */
    private $controller;  
    
    /**
     * Dispatch the request.
     *
     */
    public function dispatch()
    {
        // application controller class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaControllerAbstractPage.php' );
        // Include controller factory class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaControllerPageFactory.php' );
        // Include controller factory class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaControllerModulePageFactory.php' );
        // request interface class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaInterfaceRequest.php' );
        // http request class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaHttpRequest.php' );        
        // response interface class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaInterfaceResponse.php' );
        // http response class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaHttpResponse.php' ); 

        try
        {   
            /*
             * Set controller type
             */
            $this->config->setVar('controller_type', 'admin', false); 

            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );
            
            // create controller and agregate some objects
            $this->_init();
            
            // Build the controller methode name of the "index" controller of the "common" module
            $methode = ucfirst( $this->config->getVar('base_module') ) . 'Index';
            
            // Execute the index controller of a common module
            $this->controller->$methode();
        }
        catch(JapaPageControllerException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController($e);
        }
        catch(JapaModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            $this->userErrorController($e);
        }         
        catch(JapaViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController($e);
        } 
        catch(JapaDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController($e);
        }      
        // it dosent work yet
        catch(JapaForwardAdminControllerException $e)
        {
            // create controller and agregate some objects
            $this->_init();
            $this->controller->{$e->controller}();  
        }      

        ob_end_flush();
    }
    
    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorController( &$e )
    {
        if(strstr($this->config->getVar('message_handle'), 'SHOW'))
        {
            $methode = ucfirst( $this->config->getVar('base_module') ) . 'Error';
            $this->controller->$methode( $e->exceptionMessage );   
        }
    }    
    
    /**
     * create controller and agregate some objects
     *
     */    
    private function _init()
    {
            // create a controller factory instance
            // this instance aggregates the view factory object
            $this->controller = new JapaControllerModulePageFactory( $this->model, $this->config );
            
            // aggregate the router object
            $this->controller->model = $this->model;

            // aggregate the router object
            $this->controller->router = $this->router;
           
            // aggregate the http request object
            $this->controller->httpRequest = new JapaHttpRequest;
            
            // aggregate the http response object
            $this->controller->httpResponse = new JapaHttpResponse;
    } 
}

?>

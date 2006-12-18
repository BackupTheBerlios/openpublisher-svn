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

/*
 * SmartXmlRpcController class
 *
 */

// PEAR ajax server classes
include_once(  JAPA_LIBRARY_DIR . 'PEAR/XML/Server.php'); 
        
class JapaControllerRpcApplication extends JapaController
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
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaControllerRpcAbstractPage.php' );
        // Include controller factory class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaControllerRpcPageFactory.php' );
        // request interface class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaInterfaceRequest.php' );
        // http request class
        include_once( JAPA_LIBRARY_DIR . 'japa/JapaHttpRequest.php' );        

        try
        {
            /*
             * Set controller type
             */
            $this->config->setVar('controller_type', 'xml_rpc');   
            
            // disable output compression
            $this->config->setVar('output_compression', false);
 
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );  
            
            // create a SmartXmlRpcViewFactory instance
            // this instance aggregates the model object
            $this->controller = new JapaControllerRpcPageFactory;
            
            // aggregate the router object
            $this->controller->model = $this->model;

            // aggregate the router object
            $this->controller->router = $this->router;
           
            // aggregate the http request object
            $this->controller->httpRequest = new JapaHttpRequest;

            // set class file path
            $this->controller->setClassFilePath( $this->config->getVar('public_controllers_folder') );

            // get the controller which is associated with a request
            $controllerRequest = $this->controller->httpRequest->getParameter( 'cntr', 'get', 'alpha' ); 
            
            // validate view request
            $methode = $this->validateControllerName( $controllerRequest );
      
            // execute the requested view
            $this->controller->$methode();          
        }
        catch(SmartViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            exit;
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            exit;
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            exit;
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            exit;
        } 
        catch(SmartDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            exit;
        }            
        catch(SmartForwardPublicViewException $e)
        {
            $this->view->{$e->view}($e->data, $e->constructorData);  
        }        
        catch(SmartForwardAdminViewException $e)
        {
            die('Setup not yet done. Please run the admin web controller. Usually it is admin.php');
        }     
    }
    /**
     * Validate controller request name.
     *
     * @see dispatch() 
     */
    private function validateControllerName( $controller_name )
    {
        $debug                     = $this->config->getVar('debug');
        $default_controller        = $this->config->getVar('default_controller');
        $public_controllers_folder = $this->config->getVar('public_controllers_folder');
        
        if(preg_match("/[^a-zA-Z0-9_]/", $controller_name))
        {
            if($debug == true)
            {
                throw new JapaViewException('Wrong controller fromat: ' . $controller_name);
            }
            return $default_controller;
        }

        if(!@file_exists( $public_controllers_folder . 'Controller' . ucfirst($controller_name) . '.php'))
        {
            if($debug == true)
            {
                throw new JapaViewException('Controller class dosent exists: ' . $public_controllers_folder . 'Controller' . ucfirst($controller_name) . '.php');
            }
            return $default_controller;
        }

        return $controller_name;
    }
}

?>

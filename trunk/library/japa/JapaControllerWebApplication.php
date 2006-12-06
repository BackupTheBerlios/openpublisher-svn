<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * Web controller class
 *
 */
 
// Start output buffering
//
@ob_end_clean();
ob_start(); 
 
class JapaControllerWebApplication extends JapaController
{
    /**
     * Controller factory object
     *
     * @var object $controller
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
            $this->config->setVar('controller_type', 'web', false);   
            
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );

            // create a controllerRunner instance
            // this instance aggregates the model object
            $this->controller = new JapaControllerPageFactory;

            // aggregate the router object
            $this->controller->model = $this->model;

            // aggregate the router object
            $this->controller->router = $this->router;
           
            // aggregate the http request object
            $this->controller->httpRequest = new JapaHttpRequest;
            
            // aggregate the http response object
            $this->controller->httpResponse = new JapaHttpResponse;

            // set class file path
            $this->controller->setClassFilePath( $this->config->getVar('public_controllers_folder') );

            // get the controller which is associated with a request
            $controllerRequest = '';

            if( false === ($controllerRequest = $this->router->getVar('cntr')) )
            {
                $controller_map = $this->model->getControllerMap();
                
                // try to get a controller name from the model (modules)
                foreach($controller_map as $item_name => $module_name)
                {
                    $id_item = $this->controller->httpRequest->getParameter( $item_name, 'request', 'digits' );

                    if( false !== $id_item )
                    {                
                        $this->model->action( $module_name, 'relatedController',
                                              array($item_name => (int)$id_item,
                                                    'result' => & $controllerRequest));
                                                    
                        break;
                    }           
                }
            }
            else
            {
                // validate controller name if it comes from outside
                $controllerRequest = $this->validateControllerName( $controllerRequest );  
            }

            // set default controller
            if( empty($controllerRequest) )
            {
                $controllerRequest = $this->config->getVar('default_controller');
            }            

            // execute the requested controller
            $this->controller->$controllerRequest();          
        }
        catch(JapaViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController( $e->getMessage() );
        }
        catch(JapaModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            $this->userErrorController( $e->getMessage() );
        }         
        catch(JapaControllerException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController( $e->getMessage() );
        } 
        catch(JapaCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController( $e->getMessage() );
        } 
        catch(JapaDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorController( $e->getMessage() );
        }            
        catch(JapaForwardPublicControllerException $e)
        {
            $this->controller->{$e->controller}($e->data, $e->constructorData);  
        }        
        catch(JapaForwardAdminControllerException $e)
        {
            die('Setup not yet done. Please run the admin web controller. Usually it is <a href="admin.php">admin.php</a>');
        }  

        $buffer_level = ob_get_level ();        
        while($buffer_level > 0)
        {
            ob_end_flush();
            $buffer_level--;
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

    /**
     * Web user error controller is executed if an exception arrise
     *
     */    
    private function userErrorController( $message )
    {
        if($this->config->getVar('debug') == false)
        {
            $methode = $this->config->getVar('default_controller');
        }
        else
        {
            $methode = $this->config->getVar('error_controller');
        }
        
        try
        {        
            $this->controller->$methode( $message );   
        }
        catch(JapaViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die();
        }
        catch(JapaModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die();
        }         
        catch(JapaControllerException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die();
        } 
        catch(JapaCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die();
        } 
        catch(JapaDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die();
        }     
    }    
}

?>

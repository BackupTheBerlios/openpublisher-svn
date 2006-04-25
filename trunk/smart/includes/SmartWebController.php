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
 * Web controller class
 *
 */
class SmartWebController extends SmartController
{
    /**
     * View factory object
     *
     * @var object $view
     */
    private $view;  
         
    /**
     * Dispatch the request.
     *
     */
    public function dispatch()
    { 
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartPublicViewFactory.php' );

        try
        {
            /*
             * Set controller type
             */
            $this->config['controller_type'] = 'web';   
            
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );

            // create a viewRunner instance
            // this instance aggregates the model object
            $this->view = new SmartPublicViewFactory( $this->model, $this->config );

            // get the view which is associated with a request
            $viewRequest = '';

            if( isset($_REQUEST['view']) )
            {
                    $viewRequest = $_REQUEST['view'];
            } 
            else
            {
                // try to get a view name from the model (modules)
                foreach($this->config['view_map'] as $id_item => $module_name)
                {
                    if(isset($_REQUEST[$id_item]))
                    {
                        $this->model->action( $module_name, 'relatedView',
                                              array($id_item => (int)$_REQUEST[$id_item],
                                                    'result' => &$viewRequest));
                        break;
                    }           
                }
            }
            
            // get view request
            if( empty($viewRequest) )
            {
                $viewRequest = $this->config['default_view'];
            }            

            // validate view request
            $methode = $this->validateViewName( $viewRequest );

            // execute the requested view
            $this->view->$methode();          
        }
        catch(SmartViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            $this->userErrorView( $e->getMessage() );
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        } 
        catch(SmartDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        }            
        catch(SmartForwardPublicViewException $e)
        {
            $this->view->{$e->view}($e->data, $e->constructorData);  
        }        
        catch(SmartForwardAdminViewException $e)
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
     * Validate view request name.
     *
     * @see dispatch() 
     */
    private function validateViewName( $view_name )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $view_name))
        {
            if($this->config['debug'] == TRUE)
            {
                throw new SmartViewException('Wrong view fromat: ' . $view_name);
            }
            return $this->config['default_view'];
        }

        if(!@file_exists(SMART_BASE_DIR . $this->model->config['views_folder'] . '/View' . ucfirst($view_name) . '.php'))
        {
            if($this->config['debug'] == TRUE)
            {
                throw new SmartViewException('View class dosent exists: ' . SMART_BASE_DIR . $this->model->config['views_folder'] . 'View' . ucfirst($view_name) . '.php');
            }
            return $this->config['default_view'];
        }

        return $view_name;
    }

    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView( $message )
    {
        if($this->config['debug'] == FALSE)
        {
            $methode = $this->model->config['default_view'];
        }
        else
        {
            $methode = $this->model->config['error_view'];
        }
        
        try
        {        
            $this->view->$methode( $message );   
        }
        catch(SmartViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die('Fatal View Error');
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die('Fatal Model Error');
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal Template Error');
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal Cache Error');
        } 
        catch(SmartDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal DB Error');
        }     
    }    
}

?>

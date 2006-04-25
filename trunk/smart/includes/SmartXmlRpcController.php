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

// PEAR xml rpc server classes
include_once( SMART_BASE_DIR . 'smart/includes/PEAR/XML/RPC/Server.php');

class SmartXmlRpcController extends SmartController
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
        // we dont need output buffering
        @ob_end_clean();
        
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartXmlRpcView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartXmlRpcViewFactory.php' );

        try
        {
            /*
             * Set controller type
             */
            $this->config['controller_type'] = 'xml_rpc';   
            
            // disable output compression
            $this->config['output_compression'] = FALSE;
 
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );
            
            $this->config['views_folder'] = SMART_VIEW_FOLDER;          
            
            // create a SmartXmlRpcViewFactory instance
            // this instance aggregates the model object
            $this->view = new SmartXmlRpcViewFactory( $this->model, $this->config );

            // get the view which is associated with a request
            $viewRequest = '';

            if( isset($_REQUEST['view']) )
            {
                    $viewRequest = $_REQUEST['view'];
            } 
            // get view request
            else
            {
                $viewRequest = 'error';
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
     * Validate view request name.
     *
     * @see dispatch() 
     */
    private function validateViewName( $view_name )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $view_name))
        {
            throw new SmartViewException('Wrong view fromat: ' . $view_name);
        }

        if(!@file_exists(SMART_BASE_DIR . $this->config['views_folder'] . '/View' . ucfirst($view_name) . '.php'))
        {
            throw new SmartViewException('View class dosent exists: ' . SMART_BASE_DIR . $this->config['views_folder'] . 'View' . ucfirst($view_name) . '.php');
        }

        return $view_name;
    }
}

?>

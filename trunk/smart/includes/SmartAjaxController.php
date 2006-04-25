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
 * SmartAjaxController class
 *
 */

// PEAR ajax server classes
include_once( SMART_BASE_DIR . 'smart/includes/PEAR/HTML/AJAX/Server.php');

class SmartAjaxController extends SmartController
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
        // we dont need output buffering at this point
        @ob_end_clean();
        
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartAjaxView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartAjaxViewFactory.php' );

        try
        {
            /*
             * Set controller type
             */
            $this->config['controller_type'] = 'ajax';   
            
            // disable output compression
            $this->config['output_compression'] = FALSE;
 
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );
            
            // create new ajax server
            $this->model->ajaxServer = new HTML_AJAX_Server();                 

            // change the view folder if defined in the main controller
            if(defined('SMART_VIEW_FOLDER'))
            {
                $this->config['views_folder'] = SMART_VIEW_FOLDER;
            }
            
            // create an SmartAjaxViewFactory instance
            $this->view = new SmartAjaxViewFactory( $this->model, $this->config );

            // get the view which is associated with a request
            $viewRequest = '';

            if( isset($_REQUEST['view']) )
            {
                    $viewRequest = $_REQUEST['view'];
            }   
            
            // validate view request
            $methode = $this->validateViewName( $viewRequest );
      
            // execute the requested view
            $this->view->$methode();    
            
            // handle ajax request for a specific view
            $this->model->ajaxServer->handleRequest();
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

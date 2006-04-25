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
 * Cli controller class
 *
 */
 
// pear console Getargs ( http://pear.php.net/package/Console_Getargs/ )
include_once( SMART_BASE_DIR . 'smart/includes/PEAR/Console/Getargs.php' ); 
 
class SmartCliController extends SmartController
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
        include_once( SMART_BASE_DIR . 'smart/includes/SmartCliView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartCliViewFactory.php' );

        try
        {
            /*
             * Set controller type
             */
            $this->config['controller_type'] = 'cli';   
 
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );
            
            if(defined('SMART_VIEW_FOLDER'))
            {
                $this->config['views_folder'] = SMART_VIEW_FOLDER;
            }            
            
            // create a SmartCliViewFactory instance
            // this instance aggregates the model object
            $this->view = new SmartCliViewFactory( $this->model, $this->config );

            // get the command line view arguments -v xxx OR --view xxx
           $viewRequest = $this->getView();

            // check if a view is defined from the command line
            if( $viewRequest == FALSE )
            {
               throw new SmartViewException('No view defined: -v xxx"]');
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
            throw new SmartViewException('Wrong view fromat: ' . $view_name);
        }

        if(!@file_exists(SMART_BASE_DIR . $this->config['views_folder'] . '/View' . ucfirst($view_name) . '.php'))
        {
            throw new SmartViewException('View class dosent exists: ' . SMART_BASE_DIR . $this->config['views_folder'] . 'View' . ucfirst($view_name) . '.php');
        }

        return $view_name;
    }
    /**
     * get view from command line
     *
     */    
    private function getView()
    {
        $x = 0;
        foreach($_SERVER['argv'] as $arg)
        {
            if(preg_match("/(-v|--view)/",$arg, $res))
            {
                if($res[1] == '-v')
                {
                    return $_SERVER['argv'][++$x];
                }
                if($res[1] == '--view')
                {
                    return $_SERVER['argv'][++$x];
                }
            }
            $x++;
        }
        return FALSE;
    }
}

?>

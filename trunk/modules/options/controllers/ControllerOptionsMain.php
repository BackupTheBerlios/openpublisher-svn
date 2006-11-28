<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ControllerOptionsMain
 *
 */
 
class ControllerOptionsMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // only administrators can change options
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->viewVar['error'] = 'You dont have the rights to change global options!';
            $this->dontPerform = TRUE;
        }
    } 
    
    /**
     * Perform on the main view
     */
    public function perform()
    {
        $updateOptions     = $this->httpRequest->getParameter('updateOptions', 'post', 'alnum');
        $deletePublicCache = $this->httpRequest->getParameter('deletePublicCache', 'post', 'alnum');
        $optimize          = $this->httpRequest->getParameter('optimize', 'post', 'alnum');
        $unlockall         = $this->httpRequest->getParameter('unlockall', 'post', 'alnum');
        
        if(!empty($updateOptions))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'options','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
                
                $this->model->action( 'options','deletePublicCache'); 
            }        
        }
        elseif(!empty($deletePublicCache))
        {
                $this->model->action( 'options','deletePublicCache');         
        }
        elseif(!empty($optimize))
        {
                $this->model->broadcast( 'optimizeDbTables');         
        }
        elseif(!empty($unlockall))
        {
                $this->model->broadcast( 'unlockAll');         
        }        
        $this->setViewVars();
    }  
    
    /**
     * Set template variables
     */
    private function setViewVars()
    {
        $this->viewVar['siteUrl']                    = $this->config['site_url'];
        $this->viewVar['publicViewFolder']           = $this->config['views_folder'];
        $this->viewVar['publicStyleFolder']          = $this->config['styles_folder'];
        $this->viewVar['publicControllerFolder']     = $this->config['controllers_folder'];
        $this->viewVar['allPublicControllerFolders'] = $this->getPublicFolders( 'controllers' );
        $this->viewVar['allPublicViewFolders']       = $this->getPublicFolders( 'views' );
        $this->viewVar['allPublicStyleFolders']      = $this->getPublicFolders( 'styles' );
        $this->viewVar['rejectedFiles']        = $this->config['rejected_files'];
        $this->viewVar['maxLockTime']          = $this->config['max_lock_time'];
        $this->viewVar['recyclerTime']         = $this->config['recycler_time'];
        $this->viewVar['sessionMaxlifetime']   = $this->config['session_maxlifetime'];
        $this->viewVar['textareaRows']         = $this->config['textarea_rows'];
        $this->viewVar['serverGMT']            = $this->config['server_gmt'];
        $this->viewVar['defaultGMT']           = $this->config['default_gmt'];

        $this->viewVar['disableCache']         = $this->config['disable_cache'];
        $this->viewVar['serverTimezone']       = (int)(date("Z") / 3600);
    } 
    
    /**
     * Get all public views/templates/css folders
     */
    private function getPublicFolders( $public_folder )
    {
        $folders = array();
        if($public_folder == 'controllers')
        {
            $public_dir = JAPA_APPLICATION_DIR;
        }
        else
        {
            $public_dir = JAPA_PUBLIC_DIR;
        }
        
        $main_dir = $public_dir . $public_folder;
          
        if ( (($handle = @opendir( $main_dir ))) != FALSE )
        {
            while ( (( $_dir = readdir( $handle ) )) != false )
            {
                if ( ( $_dir == "." ) || ( $_dir == ".." ) ||  ( $_dir == ".svn" ))
                {
                    continue;
                }

                if( is_dir($main_dir.'/'.$_dir) )
                { 
                    $folders[] = $_dir . '/';
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open folder to read: ".$main_dir, E_USER_ERROR  );
        }
        
        sort( $folders );
        return $folders;
    } 
    
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->fields  = array();

        $this->site_url     = $this->httpRequest->getParameter('site_url', 'post', 'raw');
        $this->views_folder = $this->httpRequest->getParameter('view_folder', 'post', 'alnum');
        $this->styles_folder = $this->httpRequest->getParameter('style_folder', 'post', 'alnum');
        $this->controllers_folder = $this->httpRequest->getParameter('controller_folder', 'post', 'alnum');
        $this->disable_cache = $this->httpRequest->getParameter('disable_cache', 'post', 'digits');
        $this->textarea_rows = $this->httpRequest->getParameter('textarea_rows', 'post', 'digits');  
        $this->server_gmt = $this->httpRequest->getParameter('server_gmt', 'post', 'int');
        $this->default_gmt = $this->httpRequest->getParameter('default_gmt', 'post', 'int');
        $this->session_maxlifetime = $this->httpRequest->getParameter('session_maxlifetime', 'post', 'digits');
        $this->max_lock_time = $this->httpRequest->getParameter('max_lock_time', 'post', 'digits');
        $this->recycler_time = $this->httpRequest->getParameter('recycler_time', 'post', 'digits');
        $this->rejected_files = $this->httpRequest->getParameter('rejected_files', 'post', 'raw');

        if(!empty($this->site_url))
        {
            $this->fields['site_url'] = (string)$this->site_url;
            $this->config['site_url'] = (string)$this->site_url;
        }  
        
        if(!empty($this->views_folder))
        {
            $this->fields['views_folder'] = (string)$this->views_folder;
            $this->config['views_folder'] = (string)$this->views_folder;
        }  

        if(!empty($this->styles_folder))
        {
            $this->fields['styles_folder'] = (string)$this->styles_folder;
            $this->config['styles_folder'] = (string)$this->styles_folder;
        }  

        if(!empty($this->controllers_folder))
        {
            $this->fields['controllers_folder'] = (string)$this->controllers_folder;
            $this->config['controllers_folder'] = (string)$this->controllers_folder;
        }  
        
       if(!empty($this->disable_cache) && ($this->disable_cache == '1'))
       {
            $this->fields['disable_cache'] = 1;
            $this->config['disable_cache'] = 1;
       }
       else
       {
            $this->fields['disable_cache'] = 0;
            $this->config['disable_cache'] = 0;
       }

       if(!empty($this->textarea_rows) && (strlen($this->textarea_rows) <= 2))
       {
            $this->fields['textarea_rows'] = (string)$this->textarea_rows;
            $this->config['textarea_rows'] = (string)$this->textarea_rows;
       }  

       if( !empty($this->server_gmt) )
       {
            if( ($this->server_gmt >= -12) &&  ($this->server_gmt <= 12) )
            {
                $this->fields['server_gmt'] = (int)$this->server_gmt;
                $this->config['server_gmt'] = (int)$this->server_gmt;
            }
       }  
       
       if( !empty($this->default_gmt) )
       {
            if( ($this->default_gmt >= -12) &&  ($this->default_gmt <= 12) )
            {
                $this->fields['default_gmt'] = (int)$this->default_gmt;
                $this->config['default_gmt'] = (int)$this->default_gmt;
            }
       }        

       if(!empty($this->session_maxlifetime) && (strlen($this->session_maxlifetime) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $this->session_maxlifetime) )
            {
                $this->fields['session_maxlifetime'] = (int)$this->session_maxlifetime;
                $this->config['session_maxlifetime'] = (int)$this->session_maxlifetime;
            }
       } 

       if(!empty($this->max_lock_time) && (strlen($this->max_lock_time) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $this->max_lock_time) )
            {
                $this->fields['max_lock_time'] = (int)$this->max_lock_time;
                $this->config['max_lock_time'] = (int)$this->max_lock_time;
            }
       }  
       
       if(!empty($this->recycler_time) && (strlen($this->recycler_time) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $this->recycler_time) )
            {
                $this->fields['recycler_time'] = (int)$this->recycler_time;
                $this->config['recycler_time'] = (int)$this->recycler_time;
            }
       }  

       $this->fields['rejected_files'] = (string)$this->rejected_files;
       $this->config['rejected_files'] = (string)$this->rejected_files;
        
        return TRUE;
    }    
}

?>
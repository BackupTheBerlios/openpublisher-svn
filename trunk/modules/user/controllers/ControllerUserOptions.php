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
 * ControllerUserOptions class
 *
 */

class ControllerUserOptions extends JapaControllerAbstractPage
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
        // Only administrators 
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            // reload admin
            $this->router->redirect( $this->controllerVar['adminWebController'] ); 
        }
    } 

    /**
     * Execute the view
     *
     */
    public function perform()
    {
        $this->viewVar['uptodate'] = FALSE;
        $this->fields  = array();

        // add user on demande
        if( false !== $this->httpRequest->getParameter( 'updateoptions', 'post', 'alpha' ) )
        {       
            if(true == $this->validatePostData())
            {
                $this->model->action( 'common','setConfigVar',
                                      array('data'   => $this->fields,
                                            'module' => 'user')); 
                
                // reload                        
                $this->router->redirect( $this->controllerVar['adminWebController'].'/mod/user/cntr/options' );
            } 
            $this->viewVar['uptodate'] = TRUE;                           
        }

        // init users template variable 
        $this->viewVar['option'] =  $this->config->getModuleArray('user'); 
    }  
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $file_size_max = $this->httpRequest->getParameter( 'file_size_max', 'post', 'int' ); 
        $img_size_max  = $this->httpRequest->getParameter( 'img_size_max', 'post', 'int' ); 
        $thumb_width   = $this->httpRequest->getParameter( 'thumb_width', 'post', 'int' ); 
        $use_log       = $this->httpRequest->getParameter( 'use_log', 'post', 'int' ); 
        
        if(!empty($thumb_width))
        {
            if(($thumb_width > 10) && ($thumb_width <= 350))
            {
                $this->fields['thumb_width'] = (int)$thumb_width;
            }
            else
            {
                $this->viewVar['error'][] = "Thumbnail width must be between 10 and 350!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "Thumbnail width field is empty!";
        }   
        
        if(!empty($img_size_max))
        {
            if(($img_size_max > 0) && ($img_size_max <= 2000000))
            {
                $this->fields['img_size_max'] = (int)$img_size_max;
            }
            else
            {
                $this->viewVar['error'][] = "Image file size must be between 0 and 2000000!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "Image file size field is empty!";
        }  
        
        if(!empty($file_size_max))
        {
            if(($file_size_max > 0) && ($file_size_max <= 25000000))
            {
                $this->fields['file_size_max'] = (int)$file_size_max;
            }
            else
            {
                $this->viewVar['error'][] = "File size must be between 100 and 25000000!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "File size field is empty!";
        }   

        if(!empty($use_log))
        {
            $this->fields['use_log'] = 1;
        } 
        else
        {
            $this->fields['use_log'] = 0;
        }
        
        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
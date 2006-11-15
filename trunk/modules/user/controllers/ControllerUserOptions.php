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
     * Execute the view
     *
     */
    public function perform()
    {
        $this->viewVar['uptodate'] = FALSE;

        // add user on demande
        if( false !== $this->httpRequest->getParameter( 'updateoptions', 'post', 'alpha' ) )
        {       
            $file_size_max = $this->httpRequest->getParameter( 'file_size_max', 'post', 'int' ); 
            $img_size_max  = $this->httpRequest->getParameter( 'img_size_max', 'post', 'int' ); 
            $thumb_width   = $this->httpRequest->getParameter( 'thumb_width', 'post', 'int' ); 
            $use_log       = $this->httpRequest->getParameter( 'use_log', 'post', 'int' ); 
            
            // update user module options
            $this->model->action('user','updateOptions',
                                 array('file_size_max'  => (int)$_POST['file_size_max'],
                                       'img_size_max'   => (int)$_POST['img_size_max'],
                                       'thumb_width'    => (int)$_POST['thumb_width'],
                                       'use_log'        => (int)$_POST['use_log']));  

            $this->viewVar['uptodate'] = TRUE;                           
        }

        // init users template variable 
        $this->viewVar['option'] = array();
        
        // assign template variable with options of the user module
        $this->model->action('user','getOptions',
                             array('result' => & $this->viewVar['option']));  
    
    }  
}

?>
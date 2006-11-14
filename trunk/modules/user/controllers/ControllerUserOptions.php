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
 * ViewUserOptions class
 *
 */

class ViewUserOptions extends JapaControllerAbstractPage
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'options';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/user/templates/';
    
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        $this->tplVar['uptodate'] = FALSE;
        
        if(isset($_POST['updateoptions']))
        {
            // update user module options
            $this->model->action('user','updateOptions',
                                 array('file_size_max'  => (int)$_POST['file_size_max'],
                                       'img_size_max'   => (int)$_POST['img_size_max'],
                                       'thumb_width'    => (int)$_POST['thumb_width'],
                                       'use_log'        => (int)$_POST['use_log']));  

            $this->tplVar['uptodate'] = TRUE;                           
        }

        // init users template variable 
        $this->tplVar['option'] = array();
        
        // assign template variable with options of the user module
        $this->model->action('user','getOptions',
                             array('result' => & $this->tplVar['option']));  
    
    }  
}

?>
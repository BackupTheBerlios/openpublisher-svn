<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewLogout class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   TRUE or FALSE. Default = TRUE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ViewLogout extends SmartView
{
    /**
     * this view needs no template
     */
    public $renderTemplate = FALSE;
    
    /**
     * Execute the logout view
     */
    public function perform()
    {
        // Check if the visitor is a logged user
        //
        if(NULL !== ($loggedUserId = $this->model->session->get('loggedUserId')))
        {     
            // send a broadcast logout action
            $this->model->broadcast('logout',array('loggedUserId' => (int)$loggedUserId));  
        
        }
        // reload the public web controller
        @header('Location: ' . $this->config['public_web_controller']);
        exit; 
    }
}

?>
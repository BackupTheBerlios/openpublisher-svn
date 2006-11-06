<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ViewMainNavigationclass
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ControllerMainNavigation extends JapaControllerAbstractPage
{
    /**
     * Execute the view of the "mainNavigation" template
     */
    function perform()
    {
        // get 'active' root navigation nodes. Means nodes with id_parent = 0
        // we need the node titles and id_nodes
        // the result is stored in an template variable $tpl['rootNodes']
        // status 1=inactive  2=active
         
         $this->viewVar['rootNodes'] = array(); 
         // get child nodes that have id_node = 0 as id_parent
         $this->model->action( 'navigation', 'getChilds', 
                               array('id_node' => 0,
                                     'result'  => & $this->viewVar['rootNodes'],
                                     'status'  => array('>=', 2),
                                     'fields'  => array('title','id_node'))); 
                                     
        // get text for the page footer
        $this->viewVar['footer'] = array();
        $this->model->action('misc','getText', 
                             array('id_text' => 3,
                                   'result'  => & $this->viewVar['footer'],
                                   'fields'  => array('body')));                                       
    }
}

?>
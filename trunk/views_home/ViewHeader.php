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
 * ViewHeader class
 */

class ViewHeader extends SmartView
{
    /**
     * Execute the view of the "index" template
     */
    function perform()
    {  
        // get 'active' root navigation nodes. Means nodes with id_parent = 0
        // we need the node titles and id_nodes
        // the result is stored in an template variable $tpl['rootNodes']
        // status 1=inactive  2=active
         
        $this->tplVar['rootNodes'] = array(); 
        // get child nodes that have id_node = 0 as id_parent
        $this->model->action( 'navigation', 'getChilds', 
                              array('id_node' => 0,
                                    'result'  => & $this->tplVar['rootNodes'],
                                    'status'  => array('>=', 2),
                                    'fields'  => array('title','id_node'))); 
    }
}

?>
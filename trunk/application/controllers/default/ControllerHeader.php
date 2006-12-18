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
 * ControllerHeader class
 */

class ControllerHeader extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * Execute the controller of the "header" view
     */
    function perform( $data = false )
    {  
        // get 'active' root navigation nodes. Means nodes with id_parent = 0
        // we need the node titles and id_nodes
        // the result is stored in an view variable $view['rootNodes']
        // status 1=inactive  2=active
         
        $this->viewVar['rootNodes'] = array(); 
        // get root nodes which have id_node = 0 as id_parent
        $this->model->action( 'navigation', 'getChilds', 
                              array('id_node' => 0,
                                    'result'  => & $this->viewVar['rootNodes'],
                                    'status'  => array('>=', 2),
                                    'fields'  => array('title','id_node','rewrite_name')));
                                      
    }
}

?>
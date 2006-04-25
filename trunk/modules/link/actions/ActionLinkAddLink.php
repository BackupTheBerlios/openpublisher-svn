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
 * ActionLinkAddLink
 *
 * USAGE:
 * 
 * $model->action('link','addLink',
 *                array('id_node' => int,                              // required
 *                      'fields'  => array('status'      => Int,       // optional
 *                                         'title'       => String,    // required
 *                                         'description' => String,    // optional
 *                                         'url'         => String,    // required
 *                                         'hits'        => String))); // optional
 *
 *
 * return new id_link (int)
 */



class ActionLinkAddLink extends SmartAction
{  
    /**
     * Allowed link fields and its type
     */
    protected $tblFields_link = array('id_link'     => 'Int',
                                      'status'      => 'Int',
                                      'title'       => 'String',
                                      'description' => 'String',
                                      'url'         => 'String',
                                      'hits'        => 'Int');
    /**
     * Add link
     *
     */
    public function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }          
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}link_links
                   (`id_node`,$fields)
                  VALUES
                   ({$data['id_node']},$quest)";

        $this->model->dba->query($sql);                    

        // get id of the new link
        $new_id_link = $this->model->dba->lastInsertID();
       
        return $new_id_link;
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_link[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        if(!isset($data['id_node'])) 
        {
            throw new SmartModelException("'id_node' isnt defined");
        }
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt from type int");
        }         
        elseif($data['id_node'] == 0)
        {
            throw new SmartModelException("id_node=0 isnt allowed");
        }  
        
        return TRUE;
    }  
}

?>
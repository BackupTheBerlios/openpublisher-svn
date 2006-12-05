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
 * ActionMiscAddText
 *
 * USAGE:
 * 
 * $model->action('misc','addText',
 *                array('error' => & array(),
 *                      'fields' => array('status'       => 'Int',
 *                                        'format'       => 'Int',
 *                                        'media_folder' => 'String',
 *                                        'title'        => 'String',
 *                                        'description'  => 'String',
 *                                        'body'         => 'String')));
 *
 */

class ActionMiscAddText extends JapaAction
{ 
    /**
     * Fields and the format of each of the db table
     *
     */
    protected $tblFields_text = 
                      array('id_text'      => 'Int',
                            'status'       => 'Int',
                            'format'       => 'Int',
                            'media_folder' => 'String',
                            'title'        => 'String',
                            'description'  => 'String',
                            'body'         => 'String');
                            
    /**
     * Add navigation node
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
        
        $sql = "INSERT INTO {$this->config->dbTablePrefix}misc_text
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);                    
        
        // get id of the new text
        return $this->model->dba->lastInsertID();
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new JapaModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' var isnt from type array!");
        }
        
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {            
            if(!isset($this->tblFields_text[$key]))
            {
                throw new JapaModelException("Field '".$key."' isnt allowed!");
            }
        }

        if(!isset($data['fields']['title']))
        {
            throw new JapaModelException("'title' is required!");
        }
        elseif(!is_string($data['fields']['title']))
        {
            throw new JapaModelException("'title' isnt from type string!");
        }
        elseif(empty($data['fields']['title']))
        {
            $data['error'][] = 'Title is empty';      
        }

        if(isset($data['fields']['description']) && !is_string($data['fields']['description']))
        {
            throw new JapaModelException("'description' isnt from type string!");
        }

        if(isset($data['fields']['body']) && !is_string($data['fields']['body']))
        {
            throw new JapaModelException("'body' isnt from type string!");
        }

        if(isset($data['fields']['status']) && !is_int($data['fields']['status']))
        {
            throw new JapaModelException("'status' isnt from type int!");
        }

        if(isset($data['fields']['media_folder']) && !is_string($data['fields']['media_folder']))
        {
            throw new JapaModelException("'media_folder' isnt from type string!");
        }

        if( count($data['error']) > 0 )
        {
            return FALSE;
        }

        return TRUE;
    }  
}

?>
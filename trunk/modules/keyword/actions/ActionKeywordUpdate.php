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
 * ActionKeywordUpdate class 
 *
 * USAGE:
   $model->action('keyword','update',
                  array('id_key' => int,
                        'fields' => array('id_key'      => 'Int',
                                          'id_parent'   => 'Int',
                                          'status'      => 'Int',
                                          'title'       => 'String',
                                          'description' => 'String')));
 */

class ActionKeywordUpdate extends SmartAction
{
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_key'      => 'Int',
                            'id_parent'   => 'Int',
                            'status'      => 'Int',
                            'title'       => 'String',
                            'description' => 'String');
                            
    /**
     * update keyword data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}keyword
                SET
                   $fields
                WHERE
                `id_key`={$data['id_key']}";

        $this->model->dba->query($sql);                    
    
        return TRUE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
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
            if(!isset($this->tblFields_keyword[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!is_int($data['id_key']))
        {
            throw new SmartModelException('"id_key" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>

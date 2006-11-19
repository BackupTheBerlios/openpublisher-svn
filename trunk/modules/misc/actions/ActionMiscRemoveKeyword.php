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
 * ActionMiscRemoveKeyword class 
 *
 * remove id_text related id_key
 *
 * USAGE:
 *
 * $model->action('misc','removeKeyword',
 *                array('id_text' => int,
 *                      'id_key'  => int));
 *
 */
 
class ActionMiscRemoveKeyword extends JapaAction
{
    private $sqlText = '';
    private $sqlKey     = '';
    
    /**
     * delete article related key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_keyword
                  WHERE
                   {$this->sqlText}
                   {$this->sqlKey}";

        $this->model->dba->query($sql);   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(isset($data['id_text']))
        {
            if(!is_int($data['id_text']))
            {
                throw new JapaModelException('"id_text" isnt from type int');        
            }   
            $this->sqlText = "`id_text`={$data['id_text']}";
            $selcetedItem = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new JapaModelException("'id_key' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlKey = " AND ";
            }
            $this->sqlKey .= "`id_key`={$data['id_key']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new JapaModelException('Whether "id_key" nor "id_text" is defined');                      
        }
         
        return TRUE;
    }
}

?>

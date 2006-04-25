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
 * ActionLinkRemoveKeyword class 
 *
 * remove id_link related id_key
 *
 * USAGE:
 *
 * $model->action('link','removeKeyword',
 *                array('id_link' => int,
 *                      'id_key'  => int));
 *
 */
 
class ActionLinkRemoveKeyword extends SmartAction
{
    private $sqlLink = '';
    private $sqlKey  = '';
    
    /**
     * delete key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_keyword
                  WHERE
                   {$this->sqlLink}
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
        if(isset($data['id_link']))
        {
            if(!is_int($data['id_link']))
            {
                throw new SmartModelException('"id_link" isnt from type int');        
            }   
            $this->sqlLink = "`id_link`={$data['id_link']}";
            $selcetedItem  = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new SmartModelException("'id_key' isnt from type int");
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
            throw new SmartModelException('Whether "id_key" nor "id_link" is defined');                      
        }
         
        return TRUE;
    }
}

?>

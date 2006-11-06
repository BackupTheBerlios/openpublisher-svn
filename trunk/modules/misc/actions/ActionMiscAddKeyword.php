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
 * ActionMiscAddKeyword
 *
 * USAGE:
 *
 * $model->action('misc', 'addKeyword',
 *                array('id_text' => int,
 *                      'id_key'  => int) );
 * 
 */



class ActionMiscAddKeyword extends JapaAction
{                                           
    /**
     * Add keyword
     *
     */
    public function perform( $data = FALSE )
    {     
        // return if id_key is still contected to this id_text 
        if($this->isKey( $data['id_text'], $data['id_key'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}misc_keyword
                   (`id_key`,`id_text`)
                  VALUES
                   ({$data['id_key']},{$data['id_text']})";

        $this->model->dba->query($sql);                    
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_text'])) 
        {
            throw new SmartModelException("'id_text' isnt defined");
        }
        elseif(!is_int($data['id_text']))
        {
            throw new SmartModelException("'id_text' isnt from type int");
        }         
          
        if(!isset($data['id_key'])) 
        {
            throw new SmartModelException("'id_key' isnt defined");
        }
        elseif(!is_int($data['id_key']))
        {
            throw new SmartModelException("'id_key' isnt from type int");
        }  
        
        return TRUE;
    }  
    /**
     * check if id_key is contected to id_text
     *
     * @param int $id_text
     * @param int $id_key
     * @return int num Rows
     */
    private function isKey( $id_text, $id_key )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}misc_keyword
                WHERE
                   `id_text`={$id_text}
                AND
                   `id_key`={$id_key}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>
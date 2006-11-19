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
 * ActionLinkAddKeyword
 *
 * USAGE:
 *
 * $model->action('link', 'addKeyword',
 *                array('id_link' => int,
 *                      'id_key'  => int) );
 * 
 */



class ActionLinkAddKeyword extends JapaAction
{                                           
    /**
     * Add keyword
     *
     */
    public function perform( $data = FALSE )
    {     
        // return if id_key is still contected to this id_link 
        if($this->isKey( $data['id_link'], $data['id_key'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}link_keyword
                   (`id_key`,`id_link`)
                  VALUES
                   ({$data['id_key']},{$data['id_link']})";

        $this->model->dba->query($sql);                    
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_link'])) 
        {
            throw new JapaModelException("'id_link' isnt defined");
        }
        elseif(!is_int($data['id_link']))
        {
            throw new JapaModelException("'id_link' isnt from type int");
        }         
          
        if(!isset($data['id_key'])) 
        {
            throw new JapaModelException("'id_key' isnt defined");
        }
        elseif(!is_int($data['id_key']))
        {
            throw new JapaModelException("'id_key' isnt from type int");
        }  
        
        return TRUE;
    }  
    /**
     * check if id_key is contected to id_link
     *
     * @param int $id_link
     * @param int $id_key
     * @return int num Rows
     */
    private function isKey( $id_link, $id_key )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}link_keyword
                WHERE
                   `id_link`={$id_link}
                AND
                   `id_key`={$id_key}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>
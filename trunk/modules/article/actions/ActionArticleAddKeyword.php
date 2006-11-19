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
 * ActionArticleAddKeyword
 *
 * USAGE:
 *
 * $model->action('article', 'addKeyword',
 *                array('id_article' => int,
 *                      'id_key'     => int) );
 * 
 */



class ActionArticleAddKeyword extends JapaAction
{                                           
    /**
     * Add keyword
     *
     */
    public function perform( $data = FALSE )
    {     
        // return if id_key is still contected to this id_article 
        if($this->isKey( $data['id_article'], $data['id_key'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_keyword
                   (`id_key`,`id_article`)
                  VALUES
                   ({$data['id_key']},{$data['id_article']})";

        $this->model->dba->query($sql);                    
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_article'])) 
        {
            throw new JapaModelException("'id_article' isnt defined");
        }
        elseif(!is_int($data['id_article']))
        {
            throw new JapaModelException("'id_article' isnt from type int");
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
     * check if id_key is contected to id_article
     *
     * @param int $id_article
     * @param int $id_key
     * @return int num Rows
     */
    private function isKey( $id_article, $id_key )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}article_keyword
                WHERE
                   `id_article`={$id_article}
                AND
                   `id_key`={$id_key}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>
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
 * ActionArticleAddUser
 *
 * USAGE:
 *
 * $model->action('article', 'addUser',
 *                array('id_article' => int,
 *                      'id_user'    => int) );
 * 
 */

class ActionArticleAddUser extends JapaAction
{                                           
    /**
     * Add user
     *
     */
    public function perform( $data = FALSE )
    {     
        // return if id_user is associated to this id_article 
        if($this->isUser( $data['id_article'], $data['id_user'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_user
                   (`id_user`,`id_article`)
                  VALUES
                   ({$data['id_user']},{$data['id_article']})";

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
          
        if(!isset($data['id_user'])) 
        {
            throw new JapaModelException("'id_user' isnt defined");
        }
        elseif(!is_int($data['id_user']))
        {
            throw new JapaModelException("'id_user' isnt from type int");
        }  
        
        return TRUE;
    }  
    /**
     * check if id_user is associated to id_article
     *
     * @param int $id_article
     * @param int $id_user
     * @return int num Rows
     */
    private function isUser( $id_article, $id_user )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_user`
                FROM 
                  {$this->config['dbTablePrefix']}article_user
                WHERE
                   `id_article`={$id_article}
                AND
                   `id_user`={$id_user}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>
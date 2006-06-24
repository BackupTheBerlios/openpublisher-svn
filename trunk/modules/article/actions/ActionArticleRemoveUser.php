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
 * ActionArticleRemoveUser class 
 *
 * remove id_article related id_user
 *
 * USAGE:
 *
 * $model->action('article','removeUser',
 *                array('id_article' => int,
 *                      'id_user'    => int));
 *
 */
 
class ActionArticleRemoveUser extends SmartAction
{
    private $sqlArticle = '';
    private $sqlUser    = '';
    
    /**
     * delete article related user
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_user
                  WHERE
                   {$this->sqlArticle}
                   {$this->sqlUser}";

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
        if(isset($data['id_article']))
        {
            if(!is_int($data['id_article']))
            {
                throw new SmartModelException('"id_article" isnt from type int');        
            }   
            $this->sqlArticle = "`id_article`={$data['id_article']}";
            $selcetedItem = TRUE;
        }    
        
        if(isset($data['id_user'])) 
        {
            if(!is_int($data['id_user']))
            {
                throw new SmartModelException("'id_user' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlUser = " AND ";
            }
            $this->sqlUser .= "`id_user`={$data['id_user']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new SmartModelException('Whether "id_user" nor "id_article" is defined');                      
        }
         
        return TRUE;
    }
}

?>

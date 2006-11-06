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
 * ActionArticleDeleteArticle class 
 *
 * USAGE:
 *
 * $model->action('article','deleteArticle',
 *                array('id_article'  => int))
 *
 */
 
class ActionArticleDeleteComment extends JapaAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_comment
                  WHERE
                   `id_comment`={$data['id_comment']}";

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
        if(!isset($data['id_comment']))
        {
            throw new SmartModelException('"id_comment" isnt defined');        
        }    
        elseif(!is_int($data['id_comment']))
        {
            throw new SmartModelException('"id_comment" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>

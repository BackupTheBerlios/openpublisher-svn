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
 * ActionArticleDeleteNodeRelatedContent class 
 *
 * USAGE:
 *
 * $model->action('article','deleteNodeRelatedContent',
 *                array('id_node' => int))
 *
 *
 * DEPENDENCIES:
 * - $this->model->action('article','deleteArticle');
 *
 */
 
class ActionArticleDeleteNodeRelatedContent extends SmartAction
{
    /**
     * delete navigation node related articles
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {  
        $sql = "SELECT 
                    `id_article` 
                FROM {$this->config['dbTablePrefix']}article_article
                WHERE
                   `id_node`={$data['id_node']}";
                   
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $this->model->action('article','deleteArticle',
                                 array('id_article' => (int)$row['id_article'],
                                       'id_node'    => (int)$data['id_node']));
        }   

        // delete article node view relation        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_node_view_rel
                  WHERE
                   `id_node`={$data['id_node']}";

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
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }    
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
               
        return TRUE;
    }
}

?>

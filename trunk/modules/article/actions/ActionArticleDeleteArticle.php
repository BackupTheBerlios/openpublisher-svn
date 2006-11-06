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
 
class ActionArticleDeleteArticle extends JapaAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_media_pic
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_media_file
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_index
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_keyword
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_comment
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);  
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_user
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);  

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_view_rel
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);   
        
        $sql = "SELECT 
                  `media_folder`,
                  `id_node`
                FROM 
                  {$this->config['dbTablePrefix']}article_article
                WHERE
                   `id_article`={$data['id_article']}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete article data media folder
            SmartCommonUtil::deleteDirTree( JAPA_BASE_DIR.'data/article/'.$row['media_folder'] );
        }
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_article
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);
        
        // reorder node related article ranks
        $this->model->action('article','reorderRank',
                             array('id_node' => (int)$row['id_node']) );        
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }    
        elseif(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>

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
 * ActionArticleDeleteExpired class 
 *
 * USAGE:
 * $model->action('article','deleteExpired');
 *
 */
 
class ActionArticleDeleteExpired extends JapaAction
{
    /**
     * delete article with status 0=delete which the last update is 
     * older than one day
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        $expireTime = date("Y-m-d H:i:s", $this->config['gmtTime'] - $this->config['recycler_time']);
    
        // get articles with status 'delete=0' and older than 1 day
        $sql = "
            SELECT
                `id_article`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `status`=0
            AND
                `modifydate`<='{$expireTime}'";
        
        $rs = $this->model->dba->query($sql);
        
        // no articles, return
        if($rs->numRows() == 0)
        {
            return;
        }       
        
        // delete expired articles
        while($row = $rs->fetchAssoc())
        {
            $this->model->action('article','deleteArticle',
                            array('id_article'  => (int)$row['id_article']));  
            
			$this->model->broadcast('modArticleDeleteRelations', array('id_article' => (int)$row['id_article']));
        }      
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        return true;
    } 
}

?>

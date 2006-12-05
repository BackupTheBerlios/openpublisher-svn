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
 * ActionArticleMoveArticleRank class 
 *
 * USAGE:
 *
 * $model->action('article','moveArticleRank',
 *                array('id_article' => int, 
 *                      'id_node'    => int,
 *                      'dir'        => string)) // 'up' or 'down'
 *
 */
 
class ActionArticleMoveArticleRank extends JapaAction
{
    /**
     * exchange (move) navigation node rank
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $article = array();
        $this->model->action('article','getArticle',
                             array('result'     => &$article,
                                   'id_article' => (int)$data['id_article'],
                                   'fields'     => array('id_article','rank')));        
        
        if($data['dir'] == 'up')
        {
            $this->moveRankUp( $article, $data['id_node'] );        
        }
        else
        {
            $this->moveRankDown( $article, $data['id_node'] ); 
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['dir']))
        {
            throw new JapaModelException('"dir" action data var isnt defined');   
        }
        elseif(!is_string($data['dir']))
        {
            throw new JapaModelException('"dir" isnt from type string');   
        }
        
        if(($data['dir'] != 'up') && ($data['dir'] != 'down'))
        {
            throw new JapaModelException('Wrong "dir" action data var: '.$data['dir']); 
        }

        if(!isset($data['id_article']))
        {
            throw new JapaModelException('"id_article" action data var isnt defined');   
        }

        if(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }
        
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('"id_node" action data var isnt defined');   
        }

        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }        
        return TRUE;
    }
    /**
     * move rank up of an article
     *
     * @param array $article
     */      
    private function moveRankUp( &$article, $id_node )
    {
        // rank position 0 cant be moved any more up
        if( $article['rank'] == 0 )
        {
            return;
        }
        
        $error = array();
        
        // get the next upper rank article
        $nextArticle = $this->getNextIdArticle( $id_node, $article['rank'] - 1 );
        
        // exchange both article ranks
        
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$article['id_article'],
                                   'error'      => &$error,
                                   'fields'     => array('rank' => $article['rank'] - 1)));

        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$nextArticle['id_article'],
                                   'error'      => &$error,
                                   'fields'     => array('rank' => $nextArticle['rank'] + 1)));

    }
    /**
     * move rank down of an article
     *
     * @param array $article
     * @param int $id_node
     */  
    private function moveRankDown( &$article, $id_node )
    {        
        // get the next downer rank article
        $nextArticle = $this->getNextIdArticle( $id_node, $article['rank'] + 1 );
        
        // if we are at the end return
        if(!isset($nextArticle['id_article']))
        {
            return;
        }
        
        $error = array();
        
        // exchange both article ranks
        
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$article['id_article'],
                                   'error'      => &$error,
                                   'fields'     => array('rank' => $article['rank'] + 1)));

        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$nextArticle['id_article'],
                                   'error'      => &$error,
                                   'fields'     => array('rank' => $nextArticle['rank'] - 1)));

    }
    
    /**
     * get id_article and rank of an article with a specific rank and id_node
     *
     * @param int $id_node
     * @param int $rank
     */      
    private function getNextIdArticle( $id_node, $rank )
    {
        $sql = "
            SELECT
                `id_article`,
                `rank`
            FROM
                {$this->config->dbTablePrefix}article_article
            WHERE
                `id_node`={$id_node}
            AND
                `rank`={$rank}";
        
        $rs = $this->model->dba->query($sql);
        return $rs->fetchAssoc();   
    }
}

?>

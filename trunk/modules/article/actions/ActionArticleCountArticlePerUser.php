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
 * ActionArticleCountArticlePerUser class 
 * USAGE:
 *
    $model->action('article', 'countArticlePerUser',
                   array('id_user' => int,
                         'article_status' => array('=|=>|<=|<|>', int)));
 *
 */


class ActionArticleCountArticlePerUser extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';

    /**
     * count articles of a given id_user
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {   
        $sql = "
            SELECT {$this->sqlCache}
                count(aa.`id_article`) AS num_articles
            FROM
                {$this->config->dbTablePrefix}article_article AS aa,
                {$this->config->dbTablePrefix}article_user AS au
            WHERE
                aa.`status`{$data['article_status'][0]}{$data['article_status'][1]}
            AND
                aa.`id_article`=au.`id_article`
            AND
                au.`id_user`={$data['id_user']}";

        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() == 0)
        {
            return 0;
        }
        
        $row = $rs->fetchAssoc();
        
        return $row['num_articles'];
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new JapaModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        if(!isset($data['id_user']))
        {
            throw new JapaModelException('id_user isnt set'); 
        }
        if(!is_int($data['id_user']))
        {
            throw new JapaModelException('id_user isnt from type int '); 
        }
        
        if(!isset($data['article_status']))
        {
            throw new JapaModelException('article_status isnt set'); 
        }
        if(!is_array($data['article_status']))
        {
            throw new JapaModelException('article_status isnt from type array'); 
        }
        if(!preg_match("/=|=>|<=|<|>/", $data['article_status'][0]))
        {
            throw new JapaModelException('wrong article_status array[0] value: '.$data['article_status'][0]); 
        }
        if(!is_int($data['article_status'][1]))
        {
            throw new JapaModelException('article_status array[1] value isnt from type int'); 
        }
        return TRUE;
    }  
}

?>

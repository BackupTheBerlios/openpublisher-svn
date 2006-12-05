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
 * ActionArticleRelatedView class 
 *
 * USAGE:
 * $model->action( 'article', 'relatedView',
 *                 array('id_article' => int,
 *                       'result'     => & string));
 *
 *
 */
 
class ActionArticleRelatedController extends JapaAction
{
    /**
     * get article related controller
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {  
        // First check if there is a view assigned to this specific article
        if($this->config->getModuleVar('article', 'use_article_controller') == 1)
        {
            if($this->getArticleController( $data ) != FALSE )
            {
                return;
            }
        }

        // then check if there is a view assigned to the article node       
        $sql = "
            SELECT
                v.`name`
            FROM
                {$this->config->dbTablePrefix}article_article AS aa,
                {$this->config->dbTablePrefix}article_node_controller_rel AS an,
                {$this->config->dbTablePrefix}article_public_controller AS v
            WHERE
                aa.`id_article`={$data['id_article']} 
            AND
                aa.`id_node`=an.`id_node`
            AND
                an.`id_controller`=v.`id_controller`";

        $rs = $this->model->dba->query($sql);
       
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
        }
        else
        {
            $data['result'] = '';
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_article']))
        {
            return FALSE;
        }

        if(!is_int($data['id_article']))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    /**
     * get article related controller
     *
     * @param array $data
     */
    private function getArticleController( & $data )
    {   
        $sql = "
            SELECT
                v.`name`
            FROM
                {$this->config->dbTablePrefix}article_controller_rel AS an,
                {$this->config->dbTablePrefix}article_public_controller AS v
            WHERE
                an.`id_article`={$data['id_article']} 
            AND
                an.`id_controller`=v.`id_controller`";

        $rs = $this->model->dba->query($sql);
       
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
            return TRUE;
        }
        else
        {
            $data['result'] = '';
            return FALSE;
        }
    }     
}

?>

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
 * ActionArticleRemoveArticleControllerRelation class 
 *
 *
 */
 
class ActionArticleRemoveArticleControllerRelation extends JapaAction
{
    /**
     * delete article with status 0=delete which the last update is 
     * older than one day
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        // get articles with status 'delete=0' and older than 1 day
        $sql = "
            DELETE FROM
                {$this->config['dbTablePrefix']}article_controller_rel
            WHERE
                `id_article`={$data['id_article']}";
        
        $rs = $this->model->dba->query($sql);   
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
            throw new JapaModelException('"id_article" isnt defined');        
        }    
        if(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }   
        return TRUE;
    }    
}

?>

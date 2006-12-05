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
 * ActionArticleRemoveKeyword class 
 *
 * remove id_article related id_key
 *
 * USAGE:
 *
 * $model->action('article','removeKeyword',
 *                array('id_article' => int,
 *                      'id_key'     => int));
 *
 */
 
class ActionArticleRemoveKeyword extends JapaAction
{
    private $sqlArticle = '';
    private $sqlKey     = '';
    
    /**
     * delete article related key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config->dbTablePrefix}article_keyword
                  WHERE
                   {$this->sqlArticle}
                   {$this->sqlKey}";

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
                throw new JapaModelException('"id_article" isnt from type int');        
            }   
            $this->sqlArticle = "`id_article`={$data['id_article']}";
            $selcetedItem = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new JapaModelException("'id_key' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlKey = " AND ";
            }
            $this->sqlKey .= "`id_key`={$data['id_key']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new JapaModelException('Whether "id_key" nor "id_article" is defined');                      
        }
         
        return TRUE;
    }
}

?>

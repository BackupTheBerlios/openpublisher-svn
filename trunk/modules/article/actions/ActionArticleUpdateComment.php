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
 * ActionArticleUpdateArticle class 
 *
 * USAGE:
 * $model->action('article','updateArticle',
 *                array('id_article' => int,
 *                      'error'      => & array,
 *                      'fields'     => array('id_node'      => 'Int',
                                              'status'       => 'Int',
                                              'rank'         => 'Int',
                                              'pubdate'      => 'String',
                                              'changedate'   => 'String',
                                              'changestatus' => 'Int',
                                              'articledate'  => 'String',
                                              'lang'         => 'String',
                                              'title'        => 'String',
                                              'overtitle'    => 'String',
                                              'subtitle'     => 'String',
                                              'header'       => 'String',
                                              'description'  => 'String',
                                              'body'         => 'String',
                                              'ps'           => 'String',
                                              'format'       => 'Int',
                                              'logo'         => 'String',
                                              'media_folder' => 'String')))
 */
 
class ActionArticleUpdateComment extends SmartAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields = array('status' => 'Int');
                                      
    /**
     * update article
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma = ",";
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}article_comment
                SET
                   $fields
                WHERE
                `id_comment`={$data['id_comment']}";

        $stmt = $this->model->dba->query($sql);
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['id_comment']))
        {
            throw new SmartModelException('"id_comment" isnt defined');        
        }

        if(!is_int($data['id_comment']))
        {
            throw new SmartModelException('"id_comment" isnt from type int');        
        }

        return TRUE;
    }
}

?>

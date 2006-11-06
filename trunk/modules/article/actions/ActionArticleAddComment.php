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
 * ActionArticleAddComment
 *
 * USAGE:
 * 
 * $model->action('article','addComment',
 *                array('fields'  => array('id_article' => 'Int',
                                           'id_user'    => 'Int',
                                           'body'       => 'String',
                                           'author'     => 'String',
                                           'url'        => 'String',
                                           'email'      => 'String')));
 *
 *
 */



class ActionArticleAddComment extends JapaAction
{  
    /**
     * Allowed article fields and its type
     */
    protected $tblFields = array('id_article' => 'Int',
                                 'id_user'    => 'Int',
                                 'body'       => 'String',
                                 'author'     => 'String',
                                 'url'        => 'String',
                                 'email'      => 'String');
                                         
    /**
     * Add article
     *
     */
    public function perform( $data = FALSE )
    {       
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }        
         
        $fields .= $comma."`pubdate`";
        $quest  .= $comma."'{$this->config['gmtDate']}'";    
        
        $fields .= $comma."`ip`";
        $quest  .= $comma."'{$_SERVER['REMOTE_ADDR']}'";            

        $fields .= $comma."`agent`";
        $quest  .= $comma."'{$_SERVER['HTTP_USER_AGENT']}'";  

        $fields .= $comma."`status`";
        $quest  .= $comma."'{$this->model->config['article']['default_comment_status']}'";  

        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_comment
                   ($fields)
                  VALUES
                   ({$quest})";

        $this->model->dba->query($sql);
    } 
    
    /**
     * validate array data
     *
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
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        // title is required
        if(!isset($data['fields']['body']))
        {
            throw new SmartModelException("'body' isnt defined");
        }
        elseif(!is_string($data['fields']['body']))
        {
            throw new SmartModelException("'title' isnt from type string");
        }              

        if(!isset($data['fields']['id_article'])) 
        {
            throw new SmartModelException("'id_article' isnt defined");
        }
        elseif(!is_int($data['fields']['id_article']))
        {
            throw new SmartModelException("'id_article' isnt from type int");
        }                    
        
        return TRUE;
    }  
}

?>
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
 * ActionArticleCheckFolderRights
 *
 * USAGE:
 *
 * $model->action('article','checkFolderRights', array('error' => & array() )); 
 */
 
class ActionArticleCheckFolderRights extends SmartAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $article_folder = SMART_BASE_DIR . 'data/article';
        if(!is_writeable($article_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$article_folder;    
        }    
        $rss_folder = SMART_BASE_DIR . 'data/article/rss';
        if(!is_writeable($rss_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$rss_folder;    
        }          

        return TRUE;
    } 
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt defined");
        }
        if(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }
        
        return TRUE;
    }
}

?>
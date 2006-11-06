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

/* ********** Copyright notice from the author of the 
              rss builder classes on which this class is based on 
************/
//+---------------------------------------------+
//| Copyright(c) 2001-2006 Michael Wimmer                                |
//+---------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+---------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+---------------------------------------------+

/**
 * ActionArticleNodeBuildRss class 
 *
 *
 */
 
class ActionArticleNodeBuildRss extends JapaAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $rssfolder = JAPA_BASE_DIR . 'data/article/rss/';
        $rssfile   = '200_node'.$data['id_node'].'articles.xml';
        $data['rssfile'] = 'data/article/rss/200_node'.$data['id_node'].'articles.xml';

        // check expire time
        if( file_exists($rssfolder.$rssfile)  )
        {
            $cachetime = filemtime($rssfolder.$rssfile);

            if( ($cachetime != FALSE) && ((time() - $data['expire']) < $cachetime)  )
            {
                return;
            } 
        }  
        
        $rssObject = $this->model->action('common','rssBuilderInit',
                            array('filename' => 'node'.$data['id_node'].'articles.xml',
                                  'channel'  => & $data['channel'] ) );
                                                 
        foreach( $data['nodeArticles'] as $article)
        {
            $this->model->action('common','rssBuilderAddItem',
                                 array('rssObject' => & $rssObject,
                                       'about' => $data['item']['url'].$article['id_article'],
                                       'title' => $article['title']));        
        }
        
        $this->model->action('common','rssBuilderOutput',
                             array('rssObject' => & $rssObject,
                                   'format'  => 'save',
                                   'version' => '2.0',
                                   'path'    => $rssfolder  )); 
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( & $data )
    {         
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }    
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        
        if(!isset($data['expire']))
        {
            $data['expire'] = 0;        
        }    
        elseif(!is_int($data['expire']))
        {
            throw new SmartModelException('"expire" isnt from type int');        
        }        
        return TRUE;
    }
}

?>

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
 * ActionArticleCreateIndex class 
 *
 * USAGE:
 * $model->action('article','createIndex',
 *                array('id_article' => int))
 */
 
class ActionNavigationCreateIndex extends JapaAction
{                                      
    /**
     * create article index
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `title`,`short_text`,`body`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$data['id_node']}";
        
        $rs = $this->model->dba->query($sql);
        $field = $rs->fetchAssoc();
        
        $content = '';

        foreach($field as $f)
        {
            $content .= strip_tags($f).' ';
        }

        $substring = array();
   
        for($i = 0; $i <= 262140; $i = $i + 65535)
        {
            $substring[] = substr($this->model->dba->escape($content), $i, 65535);
        }
        
        $this->insert( $data['id_node'], $substring );   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }

        return TRUE;
    }
    
    /**
     * insert data for indexing
     *
     * @param int $id_article
     * @param array $content
     */    
    public function insert( $id_node, & $content )
    { 
        $sql = "REPLACE DELAYED INTO {$this->config['dbTablePrefix']}navigation_index 
                   SET  `id_node`={$id_node},
                        `text1`='{$content[0]}',
                        `text2`='{$content[1]}',
                        `text3`='{$content[2]}',
                        `text4`='{$content[3]}'";

        $this->model->dba->query($sql);    
    }    
}

?>

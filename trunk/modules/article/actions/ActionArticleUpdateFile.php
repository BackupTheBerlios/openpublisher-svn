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
 * ActionArticleUpdateFile class 
 *
 * USAGE:
 * $model->action('article','updateFile',
 *                array('action'  => string,   // delete or update
 *                      'id_file' => int,
 *                      'id_node' => int))
 *
 */
class ActionArticleUpdateFile extends JapaAction
{
    /**
     * update/delete article picture from db table
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    { 
        switch($data['action'])
        {
            case 'delete':
                    $this->delete($data);
                break;
            case 'update':
                    $this->update($data);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['action']) || (($data['action'] != 'delete') && ($data['action'] != 'update')))
        {      
            throw new SmartModelException("No/Wrong 'action' defined. Required!");
        }
        
        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined. Required!");
        }

        if(!is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt from type int");
        }

        if($data['action'] == 'delete')
        {
            if(!isset($data['id_article']))
            {
                throw new SmartModelException("No 'id_article' defined. Required!");
            }

            if(!is_int($data['id_article']))
            {
                throw new SmartModelException("'id_article' isnt from type int");
            }
        }
        
        return TRUE;
    }
    /**
     * delete picture entry from database and reorder the pictures rank structure
     *
     * @param array $data
     */  
    private function delete($data)
    {
        $sql = "
            DELETE FROM
                {$this->config['dbTablePrefix']}article_media_file
            WHERE
                id_file='{$data['id_file']}'";
        
        $this->model->dba->query($sql);    
        
        // Reorder the picture rank
        $sql = "
            SELECT `id_file` FROM
                {$this->config['dbTablePrefix']}article_media_file
            WHERE
                id_article='{$data['id_article']}'
            ORDER BY `rank` ASC";
        
        $stmt = $this->model->dba->query($sql);   
        $rank = 1;
        
        while($row = $stmt->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}article_media_file
                      SET
                        `rank`={$rank}
                    WHERE
                      `id_file`={$row['id_file']}";

            $this->model->dba->query($sql);   
            $rank++;
        }
    }
    
    private function update(&$data)
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}article_article
                  SET
                   $fields
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);                     
    }    
}

?>

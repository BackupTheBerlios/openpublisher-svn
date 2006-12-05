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
 * ActionArticleGetLastRank class 
 *
 * USAGE:
 *
 * $model->action('article','getLastRank',
 *                array('id_node'   => int,
 *                      'result'    => & int ));
 */

class ActionArticleGetLastRank extends JapaAction
{
    /**
     * get last rank of articles of a given id_node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `rank`
            FROM
                {$this->config->dbTablePrefix}article_article
            WHERE
                `id_node`={$data['id_node']}
            ORDER BY `rank` DESC
            LIMIT 1";
        
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc(); 
        if(isset($row['rank']))
        {
            $data['result'] = $row['rank'];
        }
        else
        {
            $data['result'] = FALSE;
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
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('id_node isnt defined');        
        }
        
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('id_node isnt from type int');        
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" var reference'); 
        }       

        return TRUE;
    }
}

?>

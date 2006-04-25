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
 * ActionNavigationReorderRank class 
 *
 * USAGE;
 * $model->action('article','reorderRank',
 *                array('id_node' => int) )
 *
 */

class ActionArticleReorderRank extends SmartAction
{
    /**
     * reorder article ranks of an id_node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `id_article`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_node`={$data['id_node']} 
            ORDER BY `rank` ASC";
        
        $rs = $this->model->dba->query($sql);
        
        $rank = 0;

        while($row = $rs->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}article_article
                    SET `rank`={$rank}
                    WHERE
                        `id_article`={$row['id_article']}";  
            $this->model->dba->query($sql);
            $rank++;
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
            throw new SmartModelException('"id_node" isnt defined');        
        }
        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }

        return TRUE;
    }
}

?>

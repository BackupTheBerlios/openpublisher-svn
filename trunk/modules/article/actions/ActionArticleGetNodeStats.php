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
 * ActionArticleComments class 
 * USAGE:
 *
 * $model->action('article','comments',
 *                array('result'      => & array,
 *                      'status'      => array('>|<|=|>=|<=|!=',1|2|3|4|5), // optional
 *                      'node_status' => array('>|<|=|>=|<=|!=',1|2|3), // optional
 *                      'pubdate' => array('>|<|=|>=|<=|!=', 'CURRENT_TIMESTAMP'),
 *                      'limit'   => array('perPage' => int,
 *                                         'numPage' => int),
 *                      'order'   => 'asc'|'desc',// optional
 *                      'fields   => array('id_node','id_article','status','rank',
 *                                         'activedate','inactivedate','pubdate',
 *                                         'lang','title','overtitle',
 *                                         'subtitle','header','description',
 *                                         'body','ps','fulltextbody',
 *                                         'format','media_folder') ));
 *
 */


class ActionArticleGetNodeStats extends SmartAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';

    /**
     * get articles data of a given id_node
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {
        $node_table = "";
        $node_where = "";
        
        if(isset($data['id_sector']))
        {
            $node_where  = "aa.`id_node` IN({$this->getSectorNodes( $data['id_sector'] )})\n";
            $node_where .= "AND\n";
            $node_where .= "aa.`status`>=4";
        }
        elseif(isset($data['id_node']))
        {
            $node_where  = "aa.`id_node`={$data['id_node']}\n";
            $node_where .= "AND\n";
            $node_where .= "aa.`status`>=4";
        }
        else
        {
            throw new SmartModelException('whether id_node nor id_sector is defined'); 
        }
        
        $sql = "
            SELECT {$this->sqlCache}
                count(aa.`id_article`) AS num_articles,
                MAX(aa.`pubdate`) AS last_publish_date,
                MIN(aa.`pubdate`) AS first_publish_date
            FROM
                {$this->config['dbTablePrefix']}article_article AS aa
                {$node_table}
            WHERE
                {$node_where}";

        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() == 0)
        {
            return;
        }
        
        $row = $rs->fetchAssoc();

        $data['result'] = array('num_article'        => $row['num_articles'],
                                'last_publish_date'  => $row['last_publish_date'],
                                'first_publish_date' => $row['first_publish_date']);
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new SmartModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        if(isset($data['id_sector']))
        {
            if(!is_int($data['id_sector']))
            {
                throw new SmartModelException('id_sector isnt from type int '); 
            }
        }
        if(isset($data['id_node']))
        {
            if(!is_int($data['id_node']))
            {
                throw new SmartModelException('id_node isnt from type int '); 
            }
        }
        
        return TRUE;
    }  
    
    private function & getSectorNodes( $id_sector )
    {
        $sql = "
            SELECT {$this->sqlCache}
                `id_node`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_sector`={$id_sector}
            AND
                `status`>=2
            AND
                `id_node`!={$id_sector}";

        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() == 0)
        {
            return false;
        }
        
        $nodes = "";
        $comma = "";
        while($row = $rs->fetchAssoc())
        {
            $nodes .= "{$comma}{$row['id_node']}";
            $comma = ",";
        }

        return $nodes;
    }
}

?>

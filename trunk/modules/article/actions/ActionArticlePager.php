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
 * ActionArticlePager class 
 *
 * USAGE:
 *
 * $model->action('article','pager',
 *                array('id_node'    => int,      // id node
                        'result'     => & string, // result string ref. with pager links
                        'numItems'   => int,      // total items
                        'perPage'    => int,      // items per page
                        'numPage'    => int,      // current page
                        'delta'      => int,      // delta range number
                        'url'        => string,   // the url for each link
                        'var_prefix' => string,   // prefix for the pager link var
                        'css_class'  => string    // css class for the links 
                        ));
 *
 *
 *
 */
include_once(JAPA_MODULES_DIR . 'common/includes/JapaPager.php');
 
class ActionArticlePager extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * build pager links
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // check pager variables
        if(!isset($data['perPage']) || ($data['perPage'] == 0))
        {
            $data['perPage'] = 10;
        }
        if(!isset($data['numPage']) || ($data['numPage'] == 0))
        {
            $data['numPage'] = 1;
        }      
        if(!isset($data['delta']))
        {
            $data['delta'] = 5;
        }  
        if(!isset($data['css_class']))
        {
            $data['css_class'] = 'smart_pager';
        }      
    
        if(isset($data['status']))
        {
            $sql_where = " AND a.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        if(isset($data['pubdate']))
        {
            $sql_pubdate = " AND a.`pubdate`{$data['pubdate'][0]}{$data['pubdate'][1]}()";
        }
        else
        {
            $sql_pubdate = "";
        }  

        if(isset($data['nodeStatus']))
        {
            $sql_nodestatus = " AND n.`status`{$data['nodeStatus'][0]}{$data['nodeStatus'][1]}";
        }
        else
        {
            $sql_nodestatus = "";
        } 

        $where = "a.`id_node`=n.`id_node`";
        $table = "";
        
        if(isset($data['id_node']))
        {
            $nodes = implode(",", $data['id_node']);
            $where .= " AND a.`id_node` IN({$nodes})";
        }
        
        if(isset($data['id_sector']))
        {
            $sectors = implode(",", $data['id_sector']);
            $where .= " AND n.`id_sector` IN({$sectors})";
        }
        
        if(isset($data['search']))
        {
            $search_string = $this->model->dba->escape( $data['search'] );
            $where .= " AND MATCH (i.`text1`,i.`text2`,i.`text3`,i.`text4`)
                      AGAINST ('{$search_string}' IN BOOLEAN MODE) 
                      AND i.id_article=a.id_article ";
            $table = ",{$this->config->dbTablePrefix}article_index AS i";
        }        
        
        $sql = "SELECT {$this->sqlCache}
                    count(a.`id_article`) AS numArticles
                FROM 
                    {$this->config->dbTablePrefix}article_article AS a,
                    {$this->config->dbTablePrefix}navigation_node AS n
                    {$table}
                WHERE
                   {$where}
                   
                   {$sql_where}
                   {$sql_nodestatus}
                   {$sql_pubdate}";
                   
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();    

        $config = array('numItems'   => (int)$row['numArticles'],
                        'perPage'    => (int)$data['perPage'],
                        'numPage'    => (int)$data['numPage'],
                        'delta'      => (int)$data['delta'],
                        'result'     => & $data['result'],
                        'url'        => (string)$data['url'],
                        'var_prefix' => (string)$data['var_prefix'],
                        'css_class'  => (string)$data['css_class']);        

        new JapaPager( $config );   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(isset($data['id_node']))
        {
            if(is_array($data['id_node']))
            {
                foreach($data['id_node'] as $id_node)
                {
                    if(!is_int($id_node))
                    {
                        throw new JapaModelException('"id_node" array value isnt from type int: '.$id_node); 
                    }
                }
            }
            else
            {
                throw new JapaModelException('"id_node" isnt from type array: '); 
            }
        }    

        if(isset($data['id_sector']))
        {
            if(is_array($data['id_sector']))
            {
                foreach($data['id_sector'] as $id_sector)
                {
                    if(!is_int($id_sector))
                    {
                        throw new JapaModelException('"id_sector" array value isnt from type int: '.$id_node); 
                    }
                }
            }
            else
            {
                throw new JapaModelException('"id_sector" isnt from type array: '); 
            }
        }  

        if(isset($data['search']))
        {
            if(!is_string($data['search']))
            {
                throw new JapaModelException('"search" isnt from type string');        
            }     
        }

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new JapaModelException('"status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new JapaModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
                {
                    throw new JapaModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }

        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new JapaModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }

        if(isset($data['pubdate']))
        {
            if(!is_array($data['pubdate']))
            {
                throw new JapaModelException('"pubdate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['pubdate'][0]))
                {
                    throw new JapaModelException('Wrong "pubdate" array[0] value: '.$data['pubdate'][0]); 
                }

                if(!isset($data['pubdate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['pubdate'][1]))
                {
                    throw new JapaModelException('Wrong "pubdate" array[1] value: '.$data['pubdate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
       
        if(!isset($data['result']))
        {
            throw new JapaModelException('"result" isnt defined'); 
        }          
        if(!is_string($data['result']))
        {
            throw new JapaModelException('"result" isnt from type string'); 
        }    
        if(!isset($data['url']))
        {
            throw new JapaModelException('"url" isnt defined'); 
        }          
        if(!is_string($data['url']))
        {
            throw new JapaModelException('"url" isnt from type string'); 
        }   
        if(isset($data['var_prefix']))
        {
            if(!is_string($data['var_prefix']))
            {
                throw new JapaModelException('"var_prefix" isnt from type string'); 
            }  
        }          
       
        if(isset($data['nodeStatus']))
        {
            if(!is_array($data['nodeStatus']))
            {
                throw new JapaModelException('"nodeStatus" isnt an array'); 
            }
            else
            {
                if(!isset($data['nodeStatus'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['nodeStatus'][0]))
                {
                    throw new JapaModelException('Wrong "nodeStatus" array[0] value: '.$data['nodeStatus'][0]); 
                }

                if(!isset($data['nodeStatus'][1]) || !is_int($data['nodeStatus'][1]))
                {
                    throw new JapaModelException('Wrong "nodeStatus" array[1] value: '.$data['nodeStatus'][1]); 
                }
            }
        }

        return TRUE;
    }
}

?>

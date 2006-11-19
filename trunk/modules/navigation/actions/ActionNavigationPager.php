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
include_once(JAPA_BASE_DIR . 'modules/common/includes/JapaPager.php');
 
class ActionNavigationPager extends JapaAction
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
            $sql_where = " AND n.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        if(isset($data['modifydate']))
        {
            $sql_modifydate = " AND n.`modifydate`{$data['modifydate'][0]}{$data['modifydate'][1]}()";
        }
        else
        {
            $sql_modifydate = "";
        }  

        if(isset($data['status']))
        {
            $sql_nodestatus = " AND n.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_nodestatus = "";
        } 

        $table = "";

        if(isset($data['search']))
        {
            $search_string = $this->model->dba->escape( $data['search'] );
            $where = "MATCH (i.`text1`,i.`text2`,i.`text3`,i.`text4`)
                      AGAINST ('{$search_string}' IN BOOLEAN MODE) 
                      AND i.id_node=n.id_node ";
            $table = ",{$this->config['dbTablePrefix']}navigation_index AS i";
        }        
        
        $sql = "SELECT {$this->sqlCache}
                    count(n.`id_node`) AS numNodes
                FROM 
                    {$this->config['dbTablePrefix']}navigation_node AS n
                    {$table}
                WHERE
                   {$where}
                   {$sql_where}
                   {$sql_nodestatus}
                   {$sql_modifydate}";
                   
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();    

        $config = array('numItems'   => (int)$row['numNodes'],
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
            if(!is_int($data['id_node']))
            {
                throw new JapaModelException('"id_node" isnt from type int');        
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

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
 * ActionArticleGetAllThumbs
 *
 * USAGE:
 *
 * $model->action('article','getAllThumbs',
 *                array('id_article' => int, 
 *                      'result'     => & array,
 *                      'fields'     => array('id_pic','rank','file',
 *                                            'title','description',
 *                                            'mime','size','height','width')))
 *
 */
 
class ActionArticleGetAllThumbs extends SmartAction
{
    private $tblFields_pic = array('id_pic'      => TRUE,
                                   'id_article'  => TRUE,
                                   'rank'        => TRUE,
                                   'file'        => TRUE,
                                   'width'       => TRUE,
                                   'height'      => TRUE,
                                   'title'       => TRUE,
                                   'description' => TRUE,
                                   'mime'        => TRUE,
                                   'size'        => TRUE,
                                   'media_folder' => TRUE);
    /**
     * get all picture thumbnail data of a node
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            if($f == 'media_folder')
            {
                $_fields  .= $comma.'aa.`'.$f.'`';          
            }
            else
            {
                $_fields .= $comma.'amp.`'.$f.'`';
            }
            $comma = ',';
        }

        // init sql where statements
        $node_table               = "";
        $sql_node_where           = "";
        $sql_article_where        = "amp.`id_article`=aa.`id_article`";
        $sql_articlenode_where    = "";
        $sql_articlesector_where  = "";
        $sql_article_status_where = "AND aa.`status`>=4";
        $sql_order                = "";
        $sql_limit                = "";

        if(isset($data['id_article']))
        {
            $_article_where     = implode(",", $data['id_article']);
            $sql_article_where  = "amp.`id_article` IN({$_article_where})";
            $sql_article_where .= "AND amp.`id_article`=aa.`id_article`";
        }

        if(isset($data['id_node']))
        {
            $_articlenode_where     = implode(",", $data['id_node']);
            $sql_articlenode_where  = "AND aa.`id_node` IN({$_articlenode_where})";
        }

        if(isset($data['id_sector']))
        {
            $_articlesector_where     = implode(",", $data['id_sector']);
            $sql_articlesector_where  = "AND nn.`id_sector` IN({$_articlesector_where})";
            $node_table      = ",{$this->config['dbTablePrefix']}navigation_node AS nn";
            $sql_node_where  = "AND nn.`id_node`=aa.`id_node` ";
        }

        if(isset($data['status']))
        { 
            $sql_article_status_where  = "AND aa.`id_article`=amp.`id_article`";
            $sql_article_status_where .= "AND aa.`status`{$data['status'][0]}{$data['status'][1]}";
        }

        if(isset($data['node_status']))
        {
            $node_table      = ",{$this->config['dbTablePrefix']}navigation_node AS nn";
            $sql_node_where  = "AND nn.`id_node`=aa.`id_node` "; 
            $sql_node_where .= "AND nn.`status`{$data['node_status'][0]}{$data['node_status'][1]} "; 
        }

        if(isset($data['order']))
        {
            if(preg_match("/rand/i",$data['order'][0]))
            {
                $sql_order = " ORDER BY RAND()";
            }
            else
            {        
                $sql_order = " ORDER BY amp.`{$data['order'][0]}` {$data['order'][1]}";
            }
        }   

        if(isset($data['limit']))
        { 
            if( $data['limit']['numPage'] < 1 )
            {
                $data['limit']['numPage'] = 1;
            }        
            $numPage = ($data['limit']['numPage'] - 1) * $data['limit']['perPage'];
            $sql_limit = " LIMIT {$numPage},{$data['limit']['perPage']}";
        } 

        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_media_pic AS amp,
                {$this->config['dbTablePrefix']}article_article AS aa
                {$node_table}
            WHERE
                {$sql_article_where}
                {$sql_article_status_where}
                {$sql_articlenode_where}
                {$sql_articlesector_where}
                {$sql_node_where}
                {$sql_order}
                {$sql_limit}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
        } 
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']))
        {
            throw new SmartModelException("'fields' isnt set");
        }
        elseif(!is_array($data['fields']))
        {
            throw new SmartModelException("'fields' isnt from type array");
        }
        elseif(count($data['fields']) == 0)
        {
            throw new SmartModelException("'fields' array is empty");
        }  
        
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_pic[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }

        if(isset($data['id_article']))
        {
            if(!is_array($data['id_article']))
            {
                throw new SmartModelException('"id_article" isnt an array'); 
            }
            else
            {
                foreach($data['id_article'] as $id_article)
                {
                    if(!is_int($id_article))
                    {
                        throw new SmartModelException('Wrong "id_article" array value: '.$id_article.'. Only integers accepted!'); 
                    }
                }
            }
        }
        
        if(isset($data['id_sector']))
        {
            if(!is_array($data['id_sector']))
            {
                throw new SmartModelException('"id_sector" isnt an array'); 
            }
            else
            {
                foreach($data['id_sector'] as $id_sector)
                {
                    if(!is_int($id_sector))
                    {
                        throw new SmartModelException('Wrong "id_sector" array value: '.$id_sector.'. Only integers accepted!'); 
                    }
                }
            }
        }

        if(isset($data['id_node']))
        {
            if(!is_array($data['id_node']))
            {
                throw new SmartModelException('"id_node" isnt an array'); 
            }
            else
            {
                foreach($data['id_node'] as $id_node)
                {
                    if(!is_int($id_node))
                    {
                        throw new SmartModelException('Wrong "id_node" array value: '.$id_node.'. Only integers accepted!'); 
                    }
                }
            }
        }

        if(isset($data['limit']))
        {        
            if(!isset($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt from type int'); 
            }             
            if(!isset($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt from type int'); 
            }  
            elseif( $data['limit']['perPage'] < 1 )
            {
                throw new SmartModelException('"perPage" must be >= 1');
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new SmartModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!isset($this->tblFields_pic[$data['order'][0]]) && !preg_match("/rand/i",$data['order'][0]) )
                {
                    throw new SmartModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new SmartModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = 'ASC';
                }
            }
        }

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new SmartModelException('"status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }

        if(isset($data['node_status']))
        {
            if(!is_array($data['node_status']))
            {
                throw new SmartModelException('"node_status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['node_status'][0]))
                {
                    throw new SmartModelException('Wrong "node_status" array[0] value: '.$data['node_status'][0]); 
                }

                if(!isset($data['node_status'][1]) || preg_match("/[^0-9]+/",$data['node_status'][1]))
                {
                    throw new SmartModelException('Wrong "node_status" array[1] value: '.$data['node_status'][1]); 
                }
            }
        }

        return TRUE;
    }
}

?>

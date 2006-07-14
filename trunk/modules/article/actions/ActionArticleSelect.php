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
 * ActionArticleSelect class 
 *
 * USAGE:
 * $model->action('article','select',
 *                array('result' => & array, 
 *                      'status' => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'order'  => array('rank|title|
 *                                         articledate|pubdate|
 *                                         overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields  => array('id_article','status','rank',
 *                                        'pubdate','articledate','modifydate',
 *                                        'lang','title','overtitle',
 *                                        'subtitle','header','description',
 *                                        'body','ps',
 *                                        'format','media_folder') ));
 *
 */
 
class ActionArticleSelect extends SmartAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'pubdate'      => 'String',
                                         'articledate'  => 'String',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String',
                                         'num_comments' => 'Int',
                                         'allow_comment' => 'Int',
                                         'close_comment' => 'Int');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // we need Id_article field
        if(!in_array('id_article', $data['fields'] ))
        {
            $data['fields'][] = 'id_article';
        }
        
        $get_num_comments = false;
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            if($f == 'num_comments')
            {
                $get_num_comments = TRUE;
                continue;
            }
            
            // Modify dates depended on gmt+X settings
            if(($f == 'pubdate') || ($f == 'modifydate') || ($f == 'articledate'))
            {
                $_fields .= $comma."DATE_ADD(`{$f}`,INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR) AS `{$f}`";
            }
            else
            {
                $_fields .= $comma.'`'.$f.'`';
            }
            $comma = ',';
        }
        
        $sql_status  = "";  
        $sql_pubdate = ""; 
        $sql_modifydate = "";
        $sql_articledate = "";
        
        if(isset($data['status']))
        {
            $sql_status = " AND `status`{$data['status'][0]}{$data['status'][1]}";
        }

        if(isset($data['id_article']))
        {
            $pubdate1 = $this->model->dba->escape($data['pubdate'][1]);
            $sql_pubdate = " AND `pubdate`{$data['pubdate'][0]}'{$pubdate1}'";
            if(isset($data['pubdate'][2]))
            {
                $pubdate3 = $this->model->dba->escape($data['pubdate'][3]);
                $sql_pubdate .= " AND `pubdate`{$data['pubdate'][2]}'{$pubdate3}'";            
            }
        }

        if(isset($data['pubdate']))
        {
            if($data['pubdate'][1] == "CURRENT_TIMESTAMP")
            {
                $_pdate = $this->config['gmtDate'];
            }
            else
            {
                $_pdate = $data['pubdate'][1];
            }
            $pubdate1 = $this->model->dba->escape($_pdate);
            $sql_pubdate = " AND `pubdate`{$data['pubdate'][0]}'{$pubdate1}'";
            
            if(isset($data['pubdate'][2]))
            {
                if($data['pubdate'][3] == "CURRENT_TIMESTAMP")
                {
                    $_p2date = $this->config['gmtDate'];
                }
                else
                {
                    $_p2date = $data['pubdate'][3];
                }
                $pubdate2 = $this->model->dba->escape($_p2date);
                $sql_pubdate .= " AND `pubdate`{$data['pubdate'][2]}'{$pubdate2}'";            
            }
        }

        if(isset($data['modifydate']))
        {
            if($data['modifydate'][1] == "CURRENT_TIMESTAMP")
            {
                $_mdate = $this->config['gmtDate'];
            }
            else
            {
                $_mdate = $data['modifydate'][1];
            }
            $modifydate1 = $this->model->dba->escape($data['modifydate'][1]);
            $sql_modifydate = " AND `modifydate`{$data['modifydate'][0]}'{$modifydate1}'";
            if(isset($data['modifydate'][2]))
            {
                if($data['modifydate'][3] == "CURRENT_TIMESTAMP")
                {
                    $_m2date = $this->config['gmtDate'];
                }
                else
                {
                    $_m2date = $data['modifydate'][3];
                }
                $modifydate2 = $this->model->dba->escape($_m2date);
                $sql_modifydate .= " AND `modifydate`{$data['modifydate'][2]}{$modifydate2}";            
            }
        }

        if(isset($data['articledate']))
        {
            if($data['articledate'][1] == "CURRENT_TIMESTAMP")
            {
                $_adate = $this->config['gmtDate'];
            }
            else
            {
                $_adate = $data['articledate'][1];
            }
            $articledate1 = $this->model->dba->escape($_adate);
            $sql_articledate = " AND `articledate`{$data['articledate'][0]}'{$articledate1}'";
            if(isset($data['articledate'][2]))
            {
                if($data['articledate'][3] == "CURRENT_TIMESTAMP")
                {
                    $_a2date = $this->config['gmtDate'];
                }
                else
                {
                    $_a2date = $data['articledate'][3];
                }
                $articledate2 = $this->model->dba->escape($_a2date);
                $sql_articledate .= " AND `articledate`{$data['articledate'][2]}{$articledate2}";            
            }
        }

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY title ASC";
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
        else
        {
            $sql_limit = "";
        }   

        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE 
                1=1
                {$sql_pubdate}
                {$sql_modifydate}
                {$sql_articledate}
                {$sql_status}
                {$sql_order}
                {$sql_limit}";
        
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            if($get_num_comments == TRUE)
            {
                $row['num_comments'] = $this->getNumComments( (int)$row['id_article'] );
            }
            
            if(isset($data['author']))
            {
                $row['authors'] = $this->getAuthors( $row['id_article'], $data );
            }
            $data['result'][] = $row;
        } 
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_article[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array'); 
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
            elseif( $data['limit']['perPage'] < 2 )
            {
                throw new SmartModelException('"perPage" must be >= 2');
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
                if(!isset($data['status'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || !is_int($data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
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
                if(!isset($this->tblFields_article[$data['order'][0]]))
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

        // check dates
        $dates = array('pubdate','modifydate','articledate');

        foreach($dates as $d)
        {
            if(isset($data[$d]))
            {
                if(!is_array($data[$d]))
                {
                    throw new SmartModelException("'$d' action array instruction isnt an array"); 
                }
                else
                {
                    if(isset($data[$d][0]) && isset($data[$d][1]))
                    {
                        if(!preg_match("/>|<|=|>=|<=|!=/",$data[$d][0]))
                        {
                            throw new SmartModelException("Wrong '$d' array[0] value: ".$data[$d][0]); 
                        }
                        if(!preg_match("/^[0-9]{4}/",$data[$d][1]))
                        {
                            throw new SmartModelException("Wrong '$d' array[1] value: ".$data[$d][1]); 
                        }                    
                    }
                    else
                    {
                        throw new SmartModelException("Wrong (missing) '$d' array values");
                    }
                    if(isset($data[$d][2]) && isset($data[$d][3]))
                    {
                        if(!preg_match("/>|<|=|>=|<=|!=/",$data[$d][2]))
                        {
                            throw new SmartModelException("Wrong '$d' array[2] value: ".$data[$d][2]); 
                        }
                        if(!preg_match("/^[0-9]{4}/",$data[$d][3]))
                        {
                            throw new SmartModelException("Wrong '$d' array[3] value: ".$data[$d][3]); 
                        }                    
                    }             
                }
            }
        }
        
        return TRUE;
    }
    /**
     * get article users
     *
     * @param int $id_article
     * @param array $data
     * @return array
     */       
    private function getAuthors( $id_article, & $data )
    {
        $result = array();
        
        $this->model->action('article','getArticleUsers',
                 array('result'     => & $result,
                       'id_article' => (int)$id_article,
                       'status'     => &$data['author']['status'], 
                       'order'      => &$data['author']['order'],
                       'fields'     => &$data['author']['fields'] ));
        
        return $result;
    }
}

?>

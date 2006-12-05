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
 * ActionArticleGetArticle class 
 *
 * USAGE:
 * $model->action('article','getArticle',
 *                array('id_article' => int, 
 *                      'result'     => & array, 
 *                      'status'     => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'get_view'   => bool,
 *                      'fields      => array('id_node','id_article','status','rank',
 *                                            'activedate','inactivedate','pubdate',
 *                                            'lang','title','overtitle',
 *                                            'subtitle','header','description',
 *                                            'body','ps','fulltextbody',
 *                                            'format','media_folder') ));
 *
 */
 
class ActionArticleGetArticle extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'articledate'  => 'String',
                                         'pubdate'      => 'String',
                                         'changedate'   => 'String',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'fulltextbody' => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String',
                                         'allow_comment' => 'Int',
                                         'close_comment' => 'Int',
                                         'timezone'      => 'Int');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            if($f == 'changedate')
            {
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
        
        
        
        if(isset($data['status']))
        {
            $sql_where = " AND `status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        if(isset($data['pubdate']))
        {
            if($data['pubdate'][1] == "CURRENT_TIMESTAMP")
            {
                $_date = $this->config->getVar('gmtDate');
            }
            else
            {
                $_date = $data['pubdate'][1];
            }
            $sql_pubdate = " AND `pubdate`{$data['pubdate'][0]}'{$_date}'";
        }
        else
        {
            $sql_pubdate = "";
        }  
        
        $sql = "
            SELECT {$this->sqlCache}
                {$_fields}
            FROM
                {$this->config->dbTablePrefix}article_article
            WHERE
                `id_article`={$data['id_article']} 
                {$sql_where}
                {$sql_pubdate}";
        
        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() == 0)
        {
            throw new JapaModelException('No article with id: '.$data['id_article']);
        }
        
        $data['result'] = $rs->fetchAssoc();

        if(in_array('changedate',$data['fields']))
        {
            $sql = "
                SELECT
                    DATE_ADD(`changedate`,INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR) AS `changedate`,
					`status` AS `changestatus`
                FROM
                    {$this->config->dbTablePrefix}article_changedate
                WHERE
                    `id_article`={$data['id_article']}";
        
            $rs = $this->model->dba->query($sql);
            
            if( $rs->numRows() > 0 )
            {
                $row = $rs->fetchAssoc();
                
                // adjust gmt+0 to user gmt
                $this->gmtToUserGmt( $row['changedate'] );
                
                $data['result']['changedate']   = $row['changedate'];
                $data['result']['changestatus'] = $row['changestatus'];
            }
        }   
        
        if(isset($data['get_controller']))
        {
            $sql = "
                SELECT
                    avr.`id_controller`,
                    av.`name`
                FROM
                    {$this->config->dbTablePrefix}article_controller_rel AS avr,
                    {$this->config->dbTablePrefix}article_public_controller AS av
                WHERE
                    avr.`id_article`={$data['id_article']}
                AND
                    avr.`id_controller`=av.`id_controller`";
        
            $rs = $this->model->dba->query($sql);
            
            if( $rs->numRows() > 0 )
            {
                $row = $rs->fetchAssoc();
                $data['result']['id_controller']   = $row['id_controller'];
                $data['result']['controller_name'] = $row['name'];   
            }
            else
            {
                $data['result']['id_controller']   = FALSE;
                $data['result']['controller_name'] = FALSE;            
            }
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
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_article[$val]))
            {
                throw new JapaModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt defined');        
        }
        elseif(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new JapaModelException('"result" isnt from type array'); 
        }        

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new JapaModelException('"status" isnt an array'); 
            }
            else
            {
                if(!isset($data['status'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new JapaModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || !is_int($data['status'][1]))
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

        if(isset($data['get_controller']))
        {
            if(!is_bool($data['get_controller']) )
            {
                throw new JapaModelException('"get_controller" isnt from type bool'); 
            }
        }

        return TRUE;
    }
}

?>

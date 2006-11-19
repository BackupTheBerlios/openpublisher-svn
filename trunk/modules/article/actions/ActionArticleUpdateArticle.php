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
 * ActionArticleUpdateArticle class 
 *
 * USAGE:
 * $model->action('article','updateArticle',
 *                array('id_article' => int,
 *                      'error'      => & array,
 *                      'fields'     => array('id_node'      => 'Int',
                                              'status'       => 'Int',
                                              'rank'         => 'Int',
                                              'pubdate'      => 'String',
                                              'changedate'   => 'String',
                                              'changestatus' => 'Int',
                                              'articledate'  => 'String',
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
                                              'media_folder' => 'String')))
 */
 
class ActionArticleUpdateArticle extends JapaAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'pubdate'      => 'String',
                                         'changedate'   => 'String',
                                         'changestatus' => 'Int',
                                         'articledate'  => 'String',
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
                                         'allow_comment' => 'Int',
                                         'close_comment' => 'Int');
                                      
    /**
     * update article
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            if(($key == 'changedate') || ($key == 'changestatus'))
            {
                continue;
            }
            // Modify dates depended on gmt+X settings
            elseif(($key == 'pubdate') || ($key == 'articledate'))
            {
                $fields .= $comma."`".$key."`=DATE_SUB('{$this->model->dba->escape($val)}',INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR)";
            }
            else
            {
                $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            }
            $comma = ",";
        }

        // create modifydate
        $fields .= ",`modifydate`='{$this->config['gmtDate']}'";

        $sql = "
            UPDATE {$this->config['dbTablePrefix']}article_article
                SET
                   $fields
                WHERE
                `id_article`={$data['id_article']}";
        
        $stmt = $this->model->dba->query($sql);                       
        
        if(isset($data['fields']['changedate']))
        {
            if($data['fields']['changedate'] == FALSE)
            {
                $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_changedate
                           WHERE `id_article` = {$data['id_article']}";

                $this->model->dba->query($sql);              
            }
            else
            {
                // update article changed status
                $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_changedate
                           SET `id_article` = {$data['id_article']},
                               `changedate` = DATE_SUB('{$this->model->dba->escape($data['fields']['changedate'])}',INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR),
                               `status`     = {$data['fields']['changestatus']}";

                $this->model->dba->query($sql);  
            }
        }        
        
        if(isset($data['fields']['title'])       ||
           isset($data['fields']['overtitle'])   ||
           isset($data['fields']['subtitle'])    ||
           isset($data['fields']['description']) ||
           isset($data['fields']['header'])      || 
           isset($data['fields']['body'])        ||
           isset($data['fields']['ps']))
        {
            // update article index
            $this->model->action('article','createIndex',
                                 array('id_article' => (int)$data['id_article']) );
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
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_article[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }

        // error is required
        if(!isset($data['error']))
        {
            throw new JapaModelException("'error' array isnt defined");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' isnt from type array");
        }  

        if(isset($data['fields']['title']))
        {
            if(!is_string($data['fields']['title']))
            {
                throw new JapaModelException("'title' isnt from type string");
            }        
            elseif(empty($data['fields']['title']))
            {
                $data['error'][] = "'title' is empty";
                return FALSE;
            }  
        }
        if(isset($data['fields']['id_node']))
        {
            if(!is_int($data['fields']['id_node']))
            {
                throw new JapaModelException("'id_node' isnt from type int");
            }        
            elseif($data['fields']['id_node'] == 0)
            {
                $data['error'][] = "'id_node' can not be 0";
                return FALSE;
            }  
        }  
        
        if(isset($data['fields']['changedate']))
        {
            if(!$data['fields']['changedate'] == FALSE)
            {
                if(!preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/",$data['fields']['changedate']))
                {
                    throw new JapaModelException("'changedate' isnt from type '0000-00-00 00:00'");
                }
                if(!isset($data['fields']['changestatus']))
                {
                    throw new JapaModelException("'changestatus' isnt defined");
                }     
                if(!is_int($data['fields']['changestatus']))
                {
                    throw new JapaModelException("'changestatus' isnt from type int");
                }  
                if(($data['fields']['changestatus'] < 0) || ($data['fields']['changestatus'] > 5))
                {
                    throw new JapaModelException("'changestatus' allowed value = 0-5");
                }   
            }
        }          
        return TRUE;
    }
}

?>

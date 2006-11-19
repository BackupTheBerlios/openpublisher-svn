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
 * ActionArticleGetKeywordIds
 *
 * get article related keyword Id's
 *
 * USAGE:
 *
 * $model->action('link', 'getKeywordIds',
 *                array('result'  => &$array(),
 *                      'id_link' => int,
 *                      'key_status' => array('>|<|=|>=|<=|!=',1|2) // keyword status - optional
 *                      ) );
 * 
 */

class ActionLinkGetKeywordIds extends JapaAction
{                                           
    /**
     * get article related keywords
     *
     */
    public function perform( $data = FALSE )
    { 
        if(isset($data['key_status']))
        {
            $sql_key_status  = " AND lk.`id_key`=k.`id_key`";
            $sql_key_status .= " AND k.`status`{$data['key_status'][0]}{$data['key_status'][1]}";
            $sql_key_table   = ",{$this->config['dbTablePrefix']}keyword AS k";
        }
        else
        {
            $sql_key_status = "";
            $sql_key_table  = "";
        }
        
        $sql = "SELECT SQL_CACHE
                  lk.`id_key`
                FROM 
                  {$this->config['dbTablePrefix']}link_keyword AS lk
                  {$sql_key_table}
                WHERE
                   lk.`id_link`={$data['id_link']}
                   {$sql_key_status}";

        $result = $this->model->dba->query($sql);  
        while($row = $result->fetchAssoc())
        {
            $data['result'][] = (int)$row['id_key'];
        }         
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_link'])) 
        {
            throw new JapaModelException("'id_link' isnt defined");
        }
        elseif(!is_int($data['id_link']))
        {
            throw new JapaModelException("'id_link' isnt from type int");
        }         

        if(isset($data['key_status']))
        {
            if(!is_array($data['key_status']))
            {
                throw new JapaModelException('"key_status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['key_status'][0]))
                {
                    throw new JapaModelException('Wrong "key_status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['key_status'][1]) || preg_match("/[^0-9]+/",$data['key_status'][1]))
                {
                    throw new JapaModelException('Wrong "key_status" array[1] value: '.$data['key_status'][1]); 
                }
            }
        }

        return TRUE;
    }  
}

?>
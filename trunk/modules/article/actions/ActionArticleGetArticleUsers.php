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
 * ActionArticleGetArticleUsers class 
 * USAGE:
 *
 * $model->action('article','getArticleUsers',
 *                array('result'      => & array,
 *                      'status'      => array('>|<|=|>=|<=|!=',1|2|3|4|5), // optional
 *                      'node_status' => array('>|<|=|>=|<=|!=',1|2|3), // optional
 *                      'pubdate' => array('>|<|=|>=|<=|!=', 'CURRENT_TIMESTAMP'),
 *                      'limit'   => array('perPage' => int,
 *                                         'numPage' => int),
 *                      'order'   => array('rank|title|
 *                                          articledate|pubdate|
 *                                          overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields   => array('id_node','id_article','status','rank',
 *                                         'activedate','inactivedate','pubdate',
 *                                         'lang','title','overtitle',
 *                                         'subtitle','header','description',
 *                                         'body','ps','fulltextbody',
 *                                         'format','media_folder') ));
 *
 */
include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUser.php');

class ActionArticleGetArticleUsers extends ActionUser
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'uu.`'.$f.'`';
            $comma = ',';
        }

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = " ORDER BY `role` ASC, `login` ASC";
        }          

        $role = "";
        if(isset($data['role']))
        {
            $role = " AND \n `role` {$data['role'][0]} {$data['role'][1]}";
        }
        
        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_user AS uu,
                {$this->config['dbTablePrefix']}article_user AS au
            WHERE
                au.`id_article`={$data['id_article']}
            AND
                uu.`id_user`=au.`id_user`
                {$role}
                {$sql_order}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {            
            if(isset($data['translate_role']) && isset($row['role']))
            {
                $row['role_t'] = $this->userRole[$row['role']];    
            }
            
            $data['result'][] = $row;
        } 
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_user[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['id_article']) || !is_int($data['id_article']))
        {
            throw new JapaModelException("'id_article' isnt set or isnt from type int");
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new JapaModelException("'result' isnt from type array");
        }

        if(isset($data['or_id_user']) && !is_int($data['or_id_user']))
        {
            throw new JapaModelException("'or_id_user' isnt from type int");
        }

        if(isset($data['role']))
        {
            if(!is_array($data['role']))
            {
                throw new JapaModelException('"role" action array instruction isnt an array'); 
            }
            else
            {
                if(!preg_match("/=|<|>|>=|<=/",$data['role'][0]))
                {
                    throw new JapaModelException('Wrong "role" array[0] value: '.$data['role'][0]); 
                }

                if(isset($data['role'][1]))
                {
                    // check allowed role values
                    if(!isset($this->userRole[$data['role'][1]]))
                    {
                        throw new JapaModelException('Wrong "role" array[1] value: '.$data['role'][1]); 
                    }
                }
                else
                {
                    throw new JapaModelException('"role" array[1] value isnt set.'); 
                }
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new JapaModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!preg_match("/name|lastname|login|role|status/",$data['order'][0]))
                {
                    throw new JapaModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new JapaModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = 'ASC';
                }
            }
        }
        return TRUE;
    }
}

?>

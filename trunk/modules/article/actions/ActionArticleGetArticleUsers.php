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


class ActionArticleGetArticleUsers extends SmartAction
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `id_user`
            FROM
                {$this->config['dbTablePrefix']}article_user
            WHERE
                `id_article`={$data['id_article']}";

        $rs = $this->model->dba->query($sql);
        
        $id_users = array();
        while($row = $rs->fetchAssoc())
        {            
            $id_users[] = $row['id_user'];    
        } 

        // return if no user found
        if( count($id_users) == 0 )
        {
            return;
        }

        $_user_fields =  array('result'         => & $data['result'],
                               'translate_role' => TRUE,
                               'id_user'        => & $id_users,
                               'fields'         => $data['fields']);
        
        if(isset($data['order']))
        {
            $_user_fields['order'] = $data['order'];
        }
        
        $this->model->action('user', 'getUsers', $_user_fields);  
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }

        if(!isset($data['id_article']) || !is_int($data['id_article']))
        {
            throw new SmartModelException("'id_article' isnt set or isnt from type int");
        }

        if( !isset($data['fields']) )
        {
            throw new SmartModelException("'fields' isnt set");
        }
        
        return TRUE;
    }
}

?>

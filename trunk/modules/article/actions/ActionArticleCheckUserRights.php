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
 * ActionArticleCheckUserRights class 
 * USAGE:
 *
 * $model->action('article','checkUserRights',
 *                array('id_article' => int,
 *                      'id_user'    => int));
 *
 */


class ActionArticleCheckUserRights extends JapaAction
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // get user status
        $user = array();
        $this->model->action('user','getUser',
                             array('result'  => & $user,
                                   'id_user' => (int)$data['id_user'],
                                   'fields'  => array('status')) );

        // is user active
        if($user['status'] < 2)
        {
            return false;
        }

        // check if user is associated with an article
        $sql = "
            SELECT
                count(`id_user`) AS numusers
            FROM
                {$this->config['dbTablePrefix']}article_user
            WHERE
                `id_article`={$data['id_article']}
            AND
                `id_user`={$data['id_user']}
            LIMIT 1";

        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();
        
        if($row['numusers'] == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    } 
    
    public function validate( $data = FALSE )
    {

        if(!isset($data['id_article']) || !is_int($data['id_article']))
        {
            throw new JapaModelException("'id_article' isnt set or isnt from type int");
        }
        
        if(!isset($data['id_user']) || !is_int($data['id_user']))
        {
            throw new JapaModelException("'id_user' isnt set or isnt from type int");
        }
        
        return TRUE;
    }
}

?>

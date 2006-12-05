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
 * ActionNavigationRelatedController class 
 *
 * USAGE:
 * $model->action( 'navigation', 'relatedController',
 *                 array('id_node' => int,
 *                       'result' => & string));
 *
 *
 */
 
class ActionNavigationRelatedController extends JapaAction
{
    /**
     * get navigation node related view
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {       
        $sql = "
            SELECT
                v.`name`
            FROM
                {$this->config->dbTablePrefix}navigation_node AS n,
                {$this->config->dbTablePrefix}navigation_public_controller AS v
            WHERE
                n.`id_node`={$data['id_node']} 
            AND
                n.`id_controller`=v.`id_controller`";

        $rs = $this->model->dba->query($sql);
        
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
        }
        else
        {
            $data['result'] = '';
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            return FALSE;
        }

        return TRUE;
    }
}

?>

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
 * ActionArticleGetNodeAssociatedController class 
 *
 * USAGE:
 * $model->action( 'article', 'getNodeAssociatedController',
 *                 array('id_node' => int,
 *                       'result'  => & array ));
 *
 *
 */
 
class ActionArticleGetNodeAssociatedController extends JapaAction
{
    /**
     * get article node related view
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {       
        $sql = "
            SELECT
                v.`name`,
                v.`id_controller`
            FROM
                {$this->config['dbTablePrefix']}article_node_controller_rel AS an,
                {$this->config['dbTablePrefix']}article_public_controller AS v
            WHERE
                an.`id_node`={$data['id_node']} 
            AND
                an.`id_controller`=v.`id_controller`";

        $rs = $this->model->dba->query($sql);
       
        if( $rs->numRows() > 0 )
        {
            $data['result'] = $rs->fetchAssoc();
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

        if(!is_int($data['id_node']))
        {
            return FALSE;
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
        }
        
        return TRUE;
    }
}

?>

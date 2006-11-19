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
 * ActionArticleGetNodeAssociatedView class 
 *
 * USAGE:
 * $model->action( 'article', 'getNodeAssociatedView',
 *                 array('id_node' => int,
 *                       'result'  => & array ));
 *
 *
 */
 
class ActionArticleGetNodeAssociatedView extends JapaAction
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
                v.`id_view`
            FROM
                {$this->config['dbTablePrefix']}article_node_view_rel AS an,
                {$this->config['dbTablePrefix']}article_view AS v
            WHERE
                an.`id_node`={$data['id_node']} 
            AND
                an.`id_view`=v.`id_view`";

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

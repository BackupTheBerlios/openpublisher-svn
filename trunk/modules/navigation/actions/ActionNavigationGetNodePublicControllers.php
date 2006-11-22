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
 * ActionNavigationGetNodePublicControllers
 *
 * USAGE:
 *
 * $model->action('navigation','getNodePublicViews',
 *                array('result' => & array))
 *
 */
 
class ActionNavigationGetNodePublicControllers extends JapaAction
{
    private $tblFields_view = array('id_controller' => TRUE,
                                    'name'          => TRUE,
                                    'description'   => TRUE);
    /**
     * get all registered node related public views
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_public_controller
            ORDER BY `name`";

        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() > 0)
        {
            while($row = $rs->fetchAssoc())
            {
                $data['result'][] = $row;
            }        
        }
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_view[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
        }

        return TRUE;
    }
}

?>

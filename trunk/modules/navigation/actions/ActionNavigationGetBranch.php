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
 * ActionNavigationGetBranch class 
 *
 * USAGE:
 *
 * $model->action('navigation','getBranch',
 *                array('id_node' => int,
 *                      'result'  => & array,
 *                      'fields   => array('id_node''id_parent''id_sector',
 *                                         'id_view','status','rank',
 *                                         'format','logo','media_folder',
 *                                         'lang','title','short_text',
 *                                         'body') ));
 *
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');
 
class ActionNavigationGetBranch extends ActionNavigation
{ 
    /**
     * get navigation node branch
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if($data['id_node'] == 0)
        {
            return TRUE;
        }

        // id_parent is required for internal use
        if(!in_array('id_parent',$data['fields']))
        {
            array_push($data['fields'],'id_parent');
        }

        $comma = '';
        $this->_fields = '';
        foreach ($data['fields'] as $f)
        {
            $this->_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }       

        $data['id_node'] = $this->getIdParent( $data['id_node'] );

        if($data['id_node'] == 0)
        {
            return TRUE;
        }
        
        $this->getBranch( $data );
        
        // reverse array result
        $data['result'] = array_reverse($data['result']);
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_node[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" action array instruction is required'); 
        }
        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }

        if(!isset($data['result']) || !is_array($data['result']))
        {
            throw new SmartModelException('Missing "result" array var or "result isnt defined as an array.'); 
        }
        
        return TRUE;
    }
    
    /**
     * walk recursive until the top node
     *
     * @param array $data
     */     
    private function getBranch( &$data )
    {
        $sql = "
            SELECT SQL_CACHE
                {$this->_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$data['id_node']}";

        $rs = $this->model->dba->query($sql);

        if($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
            $data['id_node']  = $row['id_parent'];
            $this->getBranch($data);
        }    
    }
    
    private function getIdParent( $id_node )
    {
        $sql = "
            SELECT SQL_CACHE
                `id_parent`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$id_node}";
        
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();
        return $row['id_parent'];    
    }
}

?>

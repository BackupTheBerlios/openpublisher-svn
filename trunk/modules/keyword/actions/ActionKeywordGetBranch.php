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
 * ActionKeywordGetBranch class 
 *
 * USAGE:
 *
 * $model->action('keyword','getBranch',
 *                array('id_key'  => int,
 *                      'result'  => & array,
 *                      'fields   => array('id_key''id_parent','status',
 *                                         'title','description') ));
 *
 */
 
class ActionKeywordGetBranch extends JapaAction
{ 
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_key'      => 'Int',
                            'id_parent'   => 'Int',
                            'status'      => 'Int',
                            'title'       => 'String',
                            'description' => 'String');
 
    /**
     * get key branch
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if($data['id_key'] == 0)
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

        $data['id_key'] = $this->getIdParent( $data['id_key'] );

        if($data['id_key'] == 0)
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
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_keyword[$val]))
            {
                throw new JapaModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_key']))
        {
            throw new JapaModelException('"id_key" action array instruction is required'); 
        }
        
        if(!is_int($data['id_key']))
        {
            throw new JapaModelException('Wrong id_key format: '.$id_user);        
        }

        if(!isset($data['result']) || !is_array($data['result']))
        {
            throw new JapaModelException('Missing "result" array var or "result isnt defined as an array.'); 
        }
        
        return TRUE;
    }
    
    /**
     * walk recursive until the top keyword
     *
     * @param array $data
     */     
    private function getBranch( &$data )
    {
        $sql = "
            SELECT SQL_CACHE
                {$this->_fields}
            FROM
                {$this->config->dbTablePrefix}keyword
            WHERE
                `id_key`={$data['id_key']}";

        $rs = $this->model->dba->query($sql);

        if($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
            $data['id_key']   = $row['id_parent'];
            $this->getBranch($data);
        }    
    }
    
    private function getIdParent( $id_key )
    {
        $sql = "
            SELECT SQL_CACHE
                `id_parent`
            FROM
                {$this->config->dbTablePrefix}keyword
            WHERE
                `id_key`={$id_key}";
        
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();
        return $row['id_parent'];    
    }
}

?>

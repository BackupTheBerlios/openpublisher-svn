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
 * ActionArticleGetPublicViews
 *
 * USAGE:
 *
 * $model->action('article','getPublicViews',
 *                array('result' => & array))
 *
 */
 
class ActionArticleGetPublicViews extends SmartAction
{
    private $tblFields_view = array('id_view'     => TRUE,
                                    'name'        => TRUE);
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
                {$this->config['dbTablePrefix']}article_view
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
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_view[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        return TRUE;
    }
}

?>

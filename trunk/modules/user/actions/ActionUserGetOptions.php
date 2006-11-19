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
 * ActionUserGetOptions class 
 *
 *
 * USAGE:
 *
 * $model->action('user','getOptions',
 *                array('result' => & array()))
 *
 */
 
class ActionUserGetOptions extends JapaAction
{
    /**
     * get all user module options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                *
            FROM
                {$this->config['dbTablePrefix']}user_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
        
        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new JapaModelException("No 'result' defined");
        }
        if(!is_array($data['result']))
        {
            throw new JapaModelException("'result' isnt from type array");
        }
        return TRUE;
    }
}

?>

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
 * ActionNavigationGetAllConfigOptions  class 
 *
 * USAGE:
 *
 * $model->action('navigation','getAllConfigOptions',
 *                array('result' => & array));
 */
 
class ActionNavigationGetAllConfigOptions extends SmartAction
{
    /**
     * get all navigation module config options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "SELECT * FROM {$this->config['dbTablePrefix']}navigation_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("No 'result' defined");
        }

        return TRUE;
    }
}

?>

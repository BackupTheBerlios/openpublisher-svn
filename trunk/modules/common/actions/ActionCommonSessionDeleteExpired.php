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
 * ActionCommonSessionDeleteExpired
 *
 * USAGE:
 * $model->action( 'common', 'sessionDeleteExpired');
 *
 */

class ActionCommonSessionDeleteExpired extends JapaAction
{
    /**
     * Delete current expired session
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $ts = time() - $this->config->getModuleVar('common', 'session_maxlifetime');
        
        $result = $this->model->dba->query(
                         "SELECT 
                             `modtime` 
                          FROM 
                             {$this->config->dbTablePrefix}common_session
                          WHERE 
                              `modtime`<{$ts}
                          AND
                              `session_id`='{$this->model->session->getId()}'");

        if($result->numRows() > 0)
        {
            $this->model->session->destroy();
        }
    }
    
    public function validate( $data = false )
    { 
        return true;
    }  
}

?>
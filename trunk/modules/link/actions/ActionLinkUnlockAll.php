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
 * ActionLinkUnlockAll class 
 *
 */

/**
 * USAGE:
 *
 *
 * $model->action( 'link', 'unlockAll' );
 *
 */
class ActionLinkUnlockAll extends SmartAction
{
    /**
     * unlock all articles
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_lock";

        $this->model->dba->query($sql);        
    }
}

?>
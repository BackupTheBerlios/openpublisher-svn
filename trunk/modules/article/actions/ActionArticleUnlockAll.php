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
 * ActionArticleUnlockAll class 
 *
 */

/**
 * USAGE:
 *
 *
 * $model->action( 'article', 'unlockAll' );
 *
 */
class ActionArticleUnlockAll extends SmartAction
{
    /**
     * unlock all articles
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock";

        $this->model->dba->query($sql);        
    }
}

?>
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
 * Setup action of the common module 
 *
 */
 
class ActionDefaultSetup extends JapaAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    { 
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                VALUES
                 ('default','Main Page',1,'0.1',1,40,'DATE: 6.5.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>')";
        $this->model->dba->query($sql);            

        return TRUE;
    } 
}

?>
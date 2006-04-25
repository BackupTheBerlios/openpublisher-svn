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
 * ActionCommonSafeHtml
 *
 */

/**
 * 
 */
class ActionCommonSafeHtml extends SmartAction
{
    /**
     * strip bad code from string
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        if(!defined('XML_HTMLSAX3'))
        {
            define('XML_HTMLSAX3', SMART_BASE_DIR . 'modules/common/includes/safehtml/');
            include_once(SMART_BASE_DIR . 'modules/common/includes/safehtml/safehtml.php');
        }
        
        $this->model->safehtml = new safehtml();
        return $this->model->safehtml->parse( $data);
    }  
}

?>
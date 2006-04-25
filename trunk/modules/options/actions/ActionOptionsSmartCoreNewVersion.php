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
 * ActionOptiuonsSmartCoreNewVersion
 *
 * Delete public views cache
 *
 * USAGE:
 * $model->action( 'options', 'smartCoreNewVersion', 
 *                 array('new_version' => (string) );
 *
 */

class ActionOptionsSmartCoreNewVersion extends SmartAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $this->model->action( 'options','deletePublicCache');
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException("'new_version' isnt from type string");
        }    
        return TRUE;
    }    
}

?>
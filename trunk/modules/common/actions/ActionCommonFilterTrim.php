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
 * ActionCommonFilterTrim
 *
 * USAGE:
 * $model->action( 'common', 'filterTrim', 
 *                 array('str' => & (string) );
 *
 */

class ActionCommonFilterTrim extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $data['str'] = trim( $data['str'] ); 
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!is_string($data['str']))
        {
            throw new JapaModelException("'str' isnt from type string");
        }    
        return TRUE;
    }    
}

?>
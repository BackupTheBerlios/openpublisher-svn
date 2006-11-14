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
 * ActionUser class 
 * Some user action classes may extends this class
 *
 */

class ActionUser extends JapaAction
{
    /**
     * Fields and its format of the db table user_user 
     *
     */
    protected $tblFields_user = 
                      array('id_user'   => 'Int',
                            'login'     => 'String',
                            'role'      => 'Int',
                            'status'    => 'Int',
                            'lock'      => 'Int',
                            'lock_time' => 'String',
                            'access'    => 'String',
                            'passwd'    => 'String',
                            'name'      => 'String',
                            'lastname'  => 'String',
                            'email'     => 'String',
                            'description'  => 'String',
                            'user_gmt'     => 'Int',
                            'format'       => 'Int',
                            'logo'         => 'String',
                            'media_folder' => 'String');

    /**
     * User role levels 
     *
     */                            
    protected $userRole = array('10' => 'Superuser',
                                '20' => 'Administrator',
                                '40' => 'Editor',
                                '60' => 'Author',
                                '80' => 'Contributor',
                                '100' => 'Webuser');      
                                
    public function perform( $data = FALSE ){}      
    public function validate( $data = FALSE )
    {
        return true;
    }            
}

?>

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
 * action_user_captcha_validate class 
 *
 * USAGE:
 *
 * $res= $this->model->action( 'common','captchaValidate',
 *                             array('turing_key'  => (string),
 *                                   'public_key'  => (string),
 *                                   'configPath'  => (string) ))
 *
 * return TRUE or FALSE
 */

// captcha class
//
include_once( JAPA_BASE_DIR .'modules/common/includes/class.captcha.php' );

 
class ActionCommonCaptchaValidate extends JapaAction
{
    /**
     * Validate capcha public key/turing key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // Captcha privat key!!!
        $captcha_privat_key = md5(implode('',file($data['configPath'].'dbConnect.php')));
        
        // The ttf font to create turing chars images
        $captcha_ttf_font = JAPA_BASE_DIR .'modules/common/includes/ttf_font/activa.ttf';
    
        // Relative folder of captcha pictures
        $captcha_pictures_folder = JAPA_PUBLIC_DIR . 'data/common/captcha';
    
        // Type of turing chars
        $captcha_char_type = 'num'; // or 'hex' 

        $captcha = new captcha( $captcha_privat_key, JAPA_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );

        if(FALSE == $captcha->check_captcha($data['public_key'], $data['turing_key']))
        {
             return FALSE;
        }

        return TRUE;
    } 
    
    public function validate( $data = FALSE )
    {
        if(!is_string($data['public_key']))
        {
            throw new SmartModelException("'public_key' isnt from type string");
        }
        if(!is_string($data['turing_key']))
        {
            throw new SmartModelException("'turing_key' isnt from type string");
        }   
        if(!is_string($data['configPath']))
        {
            throw new SmartModelException("'configPath' isnt from type string");
        }

        return TRUE;
    }
}

?>
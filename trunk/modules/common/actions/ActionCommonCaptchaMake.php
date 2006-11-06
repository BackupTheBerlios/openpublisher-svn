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
 * action_user_captcha_make class 
 *
 *
 * USAGE:
 *
 * $this->model->action( 'common','captchaMake',
 *                       array('captcha_pic' => &(string),
 *                             'public_key'  => &(string),
 *                             'configPath'  => &(string) ))
 *
 */

// captcha class
//
include_once( JAPA_BASE_DIR .'modules/common/includes/class.captcha.php' );

class ActionCommonCaptchaMake extends JapaAction
{
    /**
     * Create capcha picture and public key
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
   
        $captcha->captcha_picture_expire = 300;
        $captcha->width = 120;
        $captcha->string_len = 5;
        $captcha->shadow = FALSE;    

        $data['captcha_pic'] = $captcha->make_captcha();
        //@chmod(JAPA_BASE_DIR . $_captcha_pic, 0775);
        $data['public_key']  = $captcha->public_key;

        return TRUE;
    }
    
    public function validate( $data = FALSE )
    {
        if(!is_string($data['public_key']))
        {
            throw new SmartModelException("'public_key' isnt from type string");
        }
        if(!is_string($data['captcha_pic']))
        {
            throw new SmartModelException("'captcha_pic' isnt from type string");
        }   
        if(!is_string($data['configPath']))
        {
            throw new SmartModelException("'configPath' isnt from type string");
        }

        return TRUE;
    }    
}

?>
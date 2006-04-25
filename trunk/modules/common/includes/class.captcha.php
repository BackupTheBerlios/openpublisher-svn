<?php
// File: $Id: class.captcha.php,v 1.1.2.3 2004/04/16 10:00:30 atur Exp $
// ---------------------------------------------
// Open Publisher
// Copyright (c) 2002-2004
// by Armand Turpel
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * Captcha class
 *
 * This class is based on Julien PACHET j|u|l|i|e|n| [@] |p|a|c|h|e|t.c|o|m 
 * OCR_CAPTCHA class.
 *
 * With the methods of this class you can test if a human is 
 * behind an identifying interface. 
 *                                                                                             //
 * Info: http://www.captcha.net                                                                   //
 * 
 * This class require the GD module
 *
 * @link http://www.open-publisher.net/
 * @author Armand Turpel <contact@open-publisher.net>
 * @version $Revision: 1.1.2.3 $
 * @since 2004-03-28
 * @package default
 */

class captcha 
{ 
    /**
     * Public key string
     * @public string $public_key
     * @access privat
     */    
    public $public_key;     
    /**
     * Captcha text string
     * @public string $string_len
     */     
    public $string_len = 5; 
    /**
     * Captcha picture width
     * @public int $width
     */    
    public $width = 100; 
    /**
     * Captcha picture heigth
     * @public int $width
     */ 
    public $height = 32; 
    /**
     * Number of captcha picture background nois characters
     * @public int $nb_noise
     */ 
    public $nb_noise = 10;
    /**
     * Background grid start random color
     * @public int $grid_start_rcolor
     */    
    public $grid_start_rcolor = 50;
    /**
     * Background grid end random color
     * @public int $grid_end_rcolor
     */    
    public $grid_end_rcolor = 150;
    /**
     * Font start random size
     * @public int $font_start_rsize
     */    
    public $font_start_rsize = 20;
    /**
     * Font end random size
     * @public int $font_end_rsize
     */    
    public $font_end_rsize = 24;
    /**
     * char angle random start
     * @public int $char_rangle_start
     */    
    public $char_rangle_start = -10; 
    /**
     * char angle random end
     * @public int $char_rangle_end
     */    
    public $char_rangle_end = 10; 
    /**
     * Background color random start
     * @public int $background_color_start
     */    
    public $background_color_start = 220; 
    /**
     * Background color random end
     * @public int $background_color_end
     */    
    public $background_color_end = 250;     
    /**
     * random start space between chars
     * @public int $char_rspace_start
     */    
    public $char_rspace_start = 2; 
    /**
     * random end space between chars
     * @public int $char_rspace_end
     */    
    public $char_rspace_end = 6;      
    /**
     * Background pattern ('grid', else alphanum)
     * @public string $background_pattern 
     */    
    public $background_pattern = 'grid';
    /**
     * Create chars shadow
     * @public bool $shadow 
     */    
    public $shadow = TRUE;
    /**
     * Expire time of captcha pictures in seconds
     * The pictures are deleted if expired
     * @public string $captcha_picture_expire 
     */    
    public $captcha_picture_expire = 3600;
    /**
     * Type of turing chars (num, mixed)
     * @public string $_turing_chars
     * @access privat
     */
    public $_turing_chars = 'num';  
    /**
     * Privat key string
     * @public string $_key
     * @access privat
     */
    public $_privat_key; 
    /**
     * TTF Font (with full path)
     * @public string $_font
     * @access privat
     */     
    public $_font;
    /**
     * Relative folder path of captcha pictures
     * @public string $_captcha_pic_folder
     * @access privat
     */     
    public $_captcha_pic_folder;
    /**
     * Full path from where the script is running
     * @public string $_basedir
     * @access privat
     */     
    public $_basedir;

    /**
     * GD truecolor flag
     * @public bool $_gdtruecolor
     * @access privat
     */
    public $_gdtruecolor;
    
    private $_key = '';
    
    /**
     * Constructor
     *
     * @param string $key Privat key string
     * @param string $basedir Absolute path from where the script is running a instance of this class
     * @param string $font TTF Font (absolute path)
     * @param string $captcha_pic_folder Relative folder path of captcha pictures
     * @param string $turing_type If 'num' = numeric chars else = hex chars
     */    
    public function captcha( $privat_key, $basedir, $font, $captcha_pic_folder, $turing_type = 'num' ) 
    {
        // Init random generator
        mt_srand((double) microtime()*1000000); 
        
        // Md5 sum of the privat key
        $this->_privat_key = md5( $privat_key );  
        
        // Create type of public key
        if( 'num' == ($this->_turing_type = $turing_type))
        {
            $this->public_key = substr((string)abs(crc32(uniqid(mt_rand(),true))),0,$this->string_len);
        }
        else
        {
            $this->public_key = substr(md5(uniqid(mt_rand(),true)),0,$this->string_len);       
        }
        
        $this->_font               = $font;
        $this->_captcha_pic_folder = $captcha_pic_folder;
        $this->_basedir            = $basedir;
        
        // Check if gd true color mode is available
        $this->_check_true_color();
    }
    /**
     * get_filename
     *
     * @param  string $public Public key string
     * @return string Picture name with absolute path 
     */    
    function get_filename( $public = FALSE ) 
    {
        if ($public == FALSE)
        {
            $public=$this->public_key; 
        }
        return $this->_basedir.'data/common/captcha/'.$public.".jpg";
    }
    /**
     * generate_private
     *
     * @param  string $public Public key string
     * @return string Privat key string 
     */    
    function generate_turing_key( $public = FALSE ) 
    {
        if ($public == FALSE)
        {
            $public=$this->public_key; 
        } 
        // return type of turing key
        //
        if( 'num' == $this->_turing_type )
        {
            return substr((string)abs(crc32($this->_key.$public)),$this->string_len*-1);
        }
        else
        {
            return substr(md5($this->_privat_key.$public),$this->string_len*-1);
        } 
    }
    /**
     * check_captcha
     *
     * @param  string $public Public key string
     * @param  string $public Privat key string
     * @return bool True or false 
     */
    function check_captcha($public, $turing_key) 
    {
        if(strtolower($turing_key) == strtolower($this->generate_turing_key($public)))
        {
          return TRUE;
        }
        return FALSE;
    }
    /**
     * make_captcha
     * Make the captcha picture
     *
     * @return string Captcha image (relative path)
     */
    function make_captcha() 
    {
        // Delete expired captcha pictures
        $this->_delete_expired_files();
        // Get the turing key string
        $turing_key = $this->generate_turing_key();
        $image = $this->_imagecreate($this->width,$this->height);
        // Background color
        $back=ImageColorAllocate($image,intval(mt_rand($this->background_color_start,$this->background_color_end)),intval(mt_rand($this->background_color_start,$this->background_color_end)),intval(mt_rand($this->background_color_start,$this->background_color_end)));
        ImageFilledRectangle($image,0,0,$this->width,$this->height,$back);
        
        // Create background image
        if( $this->background_pattern != 'grid' ) 
        {
            for($i=0;$i<$this->nb_noise;$i++) 
            {
                $size=intval(mt_rand(10,35));
                $angle=intval(mt_rand(0,360));
                $x=intval(mt_rand(10,$this->width+10));
                $y=intval(mt_rand(10,$this->height+10));
                $color=ImageColorAllocate($image,intval(mt_rand(180,220)),intval(mt_rand(180,220)),intval(mt_rand(180,220)));
                $text=chr(intval(mt_rand(32,255)));
                ImagettfText($image,$size,$angle,$x,$y,$color,$this->_font,$text);
            }
        }
        else 
        { 
            for ($i=intval(mt_rand(2,6));$i<$this->width;$i+=intval(mt_rand(5,12))) 
            {
                $color=imagecolorallocate($image,intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)),intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)),intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)));
                imageline($image,$i,0,$i,$this->height,$color);
            }
            for ($i=intval(mt_rand(2,6));$i<$this->height;$i+=intval(mt_rand(5,12))) 
            {
                $color=imagecolorallocate($image,intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)),intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)),intval(mt_rand($this->grid_start_rcolor,$this->grid_end_rcolor)));
                imageline($image,0,$i,$this->width,$i,$color);
            }
        }
        //Create turing key image
        for ($i=0,$x=3; $i<$this->string_len;$i++) 
        {
            $r=intval(mt_rand(0,100));
            $g=intval(mt_rand(0,100));
            $b=intval(mt_rand(0,100));
            $color = ImageColorAllocate($image, $r,$g,$b);
            $shadow= ImageColorAllocate($image, $r+intval(mt_rand(90,128)), $g+intval(mt_rand(90,128)), $b+intval(mt_rand(90,128)));
            $size=intval(mt_rand($this->font_start_rsize,$this->font_start_rsize));
            $angle=intval(mt_rand($this->char_rangle_start,$this->char_rangle_end));
            $text=strtoupper(substr($turing_key,$i,1));
            $y = intval(mt_rand(23,28));
            if($this->shadow == TRUE)
            {
                ImagettfText($image,$size,$angle,$x+intval(mt_rand(1,3)),$y+intval(mt_rand(1,3)),$shadow,$this->_font,$text);
            }
            ImagettfText($image,$size,$angle,$x,$y,$color,$this->_font,$text);
            $x+=$size+intval(mt_rand($this->char_rspace_start,$this->char_rspace_end));
        }
        ImageJPEG($image, $this->get_filename(), 100);
        ImageDestroy($image);
        
        return $this->_captcha_pic_folder.'/'.$this->public_key.'.jpg';
    }
    /**
     * _delete_expired_files
     *
     * Delete expired captcha pictures
     * @access privat
     */    
    function _delete_expired_files()
    {
        $directory = dir($this->_basedir.'data/common/captcha');
    
        while (false != ($filename = $directory->read()))
        {
            if ( ( $filename == "." ) || ( $filename == ".." ) || ( FALSE == strstr( $filename, '.jpg') ) )
            {
                continue;
            }     
            $file = $this->_basedir.'data/common/captcha/'.$filename;

            if(TRUE == is_file($file))
            {
                if((time()-$this->captcha_picture_expire) > filemtime($file))
                {
                        @unlink($file);
                } 
            }  
        }

        $directory->close(); 
    }
    /**
     * _imagecreate
     *
     * Abstracted function of the imagecreate gd function
     * Works with gd versions prior to 2.0.1
     * @access privat
     */ 
    function _imagecreate($width,$height)
    {
        if(TRUE == $this->_gdtruecolor)
        {
            return imagecreatetruecolor($width,$height);
        }
        else
        {
            return imagecreate($width,$height);
        }    
    }
    /**
     * _check_true_color
     *
     * Check if GD version >= 2.0.1
     * 
     * @access privat
     */
    function _check_true_color()
    {
        if (FALSE == @imageCreateTrueColor(1, 1)) 
        { 
            $this->_gdtruecolor = FALSE;
        }
        else
        {
            $this->_gdtruecolor = TRUE;
        }    
    }
}
  
?>

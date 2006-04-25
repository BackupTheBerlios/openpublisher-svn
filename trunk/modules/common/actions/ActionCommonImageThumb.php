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
 * Test action (simple example)
 *
 * USAGE:
 *
 * $->model->action('common','imageThumb',
 *                  array('imgSource'     => (string), // absolute path to the source picture
 *                        'imgDestName'   => (string), // name of the thumnail file name
 *                        'imgDestWidth'  => (int),    // thumnail width
 *                        'imgDestFolder' => (string), // absolute path of the thumbnail folder
 *                        'info'          => &array)); // info about the image
 *
 */

class ActionCommonImageThumb extends SmartAction
{
    private $allowedImageTypes = array(1 => 'GIF',
                                       2 => 'JPEG',
                                       3 => 'PNG');
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        $this->imgDestHeight = (int) (($data['imgDestWidth'] / $this->imgSourceWidth) * $this->imgSourceHeight);    
        
        $destImage = $data['imgDestFolder'].'/'.$data['imgDestName'];
        
        switch($this->imgSourceType)
        {
            case 1:
                $this->resizeGIF( $data['imgSource'], $destImage, $data['imgDestWidth'] );
                break;
            case 2:
                $this->resizeJEPG( $data['imgSource'], $destImage, $data['imgDestWidth'] );
                break;
            case 3:
                $this->resizePNG( $data['imgSource'], $destImage, $data['imgDestWidth'] );
        }
        
        if(isset($data['info']))
        {
            $data['info']['mime']    = $this->imgSourceMime;
            $data['info']['size']    = $this->imgSourceSize;
            $data['info']['type']    = $this->imgSourceType;
            $data['info']['width']   = $this->imgSourceWidth;
            $data['info']['height']  = $this->imgSourceHeight;
            $data['info']['twidth']  = $data['imgDestWidth'];
            $data['info']['theight'] = $this->imgDestHeight;            
        }
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' var isnt from type array!");
        }
        
        if(!isset($data['imgDestWidth']))
        {
            throw new SmartModelException("'imgDestWidth' isnt defined");
        }
        if(!is_int($data['imgDestWidth']))
        {
            throw new SmartModelException("'imgDestWidth' isnt from type int");
        }
        
        if(!isset($data['imgDestName']))
        {
            throw new SmartModelException("'imgDestName' isnt defined");
        }        
        if(!is_string($data['imgDestName']))
        {
            throw new SmartModelException("'imgDestName' isnt from type string");
        }  
        
        if(!isset($data['imgSource']))
        {
            throw new SmartModelException("'imgSource' isnt defined");
        }        
        if(!is_string($data['imgSource']))
        {
            throw new SmartModelException("'imgSource' isnt from type string");
        }         
        if(!file_exists($data['imgSource']))
        {
            $data['error'][] = "No Image Source. Please contact the administrator";
        }

        if(!isset($data['imgDestFolder']))
        {
            throw new SmartModelException("'imgDestFolder' isnt defined");
        }        
        if(!is_string($data['imgDestFolder']))
        {
            throw new SmartModelException("'imgDestFolder' isnt from type string");
        } 
        if(!is_dir($data['imgDestFolder']))
        {
            throw new SmartModelException("'imgDestFolder' isnt a directory");
        }        
        if(!is_writeable($data['imgDestFolder']))
        {
            $data['error'][] = "Image destination folder isnt writeable. Please contact the administrator";
        }        
        
        $this->getImageInfo($data['imgSource']);
        
        if(!isset($this->allowedImageTypes[$this->imgSourceType]))
        {
            $data['error'][] = "This image type isnt supported: ".$this->imgSourceType.' '.$this->imgSourceMime;
        }

        if(count($data['error']) > 0)
        {
            return FALSE;
        }

        return TRUE;
    }    

    private function getImageInfo( &$image )
    {
        $img_info = getimagesize($image);
        $this->imgSourceWidth  = $img_info[0];
        $this->imgSourceHeight = $img_info[1];
        $this->imgSourceType   = $img_info[2];
        $this->imgSourceMime   = $img_info['mime'];
        $this->imgSourceSize   = filesize($image);
    }
    
    private function resizeJEPG( $jepgFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreatetruecolor($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefromjpeg($jepgFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagejpeg($destImage, $newImageFile, 100);    
    }
    
    private function resizePNG( $pngFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreatetruecolor($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefrompng($pngFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagepng($destImage, $newImageFile);    
    }   
    
    private function resizeGIF( $gifFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreate($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefromgif($gifFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagegif($destImage, $newImageFile);    
    }    
}

?>
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
 * ActionMiscDeleteItem class 
 *
 * USAGE:
 *
 * $model->action('misc','deleteItem',
 *                array('id_pic'  => int, // one of both param
 *                      'id_file' => int))
 */

class ActionMiscDeleteItem extends JapaAction
{
    /**
     * Delete misc text picture or file
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        // set table name and item reference
        if(isset($data['id_file']))
        {
            $this->deleteFile($data);
        }
        elseif(isset($data['id_pic']))
        {
            $this->deletePicture($data);
        }
        else
        {
            throw new SmartModelException("No 'id_file' or 'id_pic'");
        }
        
        $this->removeEmptyDirectory();
  
        return TRUE;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if(!isset($data['id_pic']) && !isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_pic' or 'id_file' defined");
        }
        
        if(isset($data['id_pic']) && !is_int($data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt from type int");
        }
        elseif(isset($data['id_file']) && !is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt from type int");
        }        
        return TRUE;
    } 
    /**
     * delete a picture
     *
     * @param array $data
     * @return bool
     */     
    private function deletePicture( &$data )
    {
        $pic = array();

        $this->model->action('misc','getPicture',
                             array('result' => & $pic,
                                   'id_pic' => (int)$data['id_pic'],
                                   'fields' => array('file','id_text')));   

        $text = array();

        $this->model->action('misc','getText',
                             array('result'  => & $text,
                                   'id_text' => (int)$pic['id_text'],
                                   'fields'  => array('media_folder')));   

        $this->idText = $pic['id_text'];
        $this->mediaFolder = &$text['media_folder'];

        if(!@unlink(JAPA_BASE_DIR . 'data/misc/'.$text['media_folder'].'/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/misc/'.$text['media_folder'].'/'.$pic['file'], E_USER_WARNING);
        }
        if(!@unlink(JAPA_BASE_DIR . 'data/misc/'.$text['media_folder'].'/thumb/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/misc/'.$text['media_folder'].'/thumb/'.$pic['file'], E_USER_WARNING);
        }    
        // remove picture reference from database
        $this->model->action('misc','updatePicture',
                             array('action'  => 'delete',
                                   'id_pic'  => (int)$data['id_pic'],
                                   'id_text' => (int)$pic['id_text']));    
    }  
    /**
     * delete a file
     *
     * @param array $data
     * @return bool
     */      
    private function deleteFile( &$data )
    {
        $file = array();

        $this->model->action('misc','getFile',
                             array('result'  => & $file,
                                   'id_file' => (int)$data['id_file'],
                                   'fields'  => array('file','id_text')));   

        $text = array();

        $this->model->action('misc','getText',
                             array('result'  => & $text,
                                   'id_text' => (int)$file['id_text'],
                                   'fields'  => array('media_folder')));   

        $this->idText = $file['id_text'];
        $this->mediaFolder = &$text['media_folder'];

        if(!@unlink(JAPA_BASE_DIR . 'data/misc/'.$text['media_folder'].'/'.$file['file']))
        {
           trigger_error('Cant delete user logo: data/misc/'.$text['media_folder'].'/'.$file['file'], E_USER_WARNING);
        }   
        // remove file reference from database
        $this->model->action('misc','updateFile',
                             array('action'  => 'delete',
                                   'id_file' => (int)$data['id_file'],
                                   'id_text' => (int)$file['id_text']));    
    }
    /**
     * remove empty user directory
     *
     * @return bool
     */  
    private function removeEmptyDirectory()
    {
        $dir = JAPA_BASE_DIR . 'data/misc/' . $this->mediaFolder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'misc','updateText',
                                  array('id_text' => (int)$this->idText,
                                        'fields'  => array('media_folder' => '')) );
        }
    }
    /**
     * check if user directory is empty
     *
     * @param string $dir whole dir path
     * @return bool
     */     
    private function isDirEmpty( &$dir )
    {
        if ( (($handle = @opendir( $dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) || is_dir($dir . '/' . $file)  )
                {
                    continue;
                }
                if ( file_exists( $dir . '/' . $file ) )
                {
                    return FALSE;
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open dir: {$dir}", E_USER_ERROR  );
            return FALSE;
        }  
        return TRUE;
    }
}

?>
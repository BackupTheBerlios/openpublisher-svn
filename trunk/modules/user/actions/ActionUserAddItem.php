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
 * ActionUserAddItem class 
 * USAGE:
 * 
 * $model->action('user','addItem',
 *                array('error' => & array(),
 *                      'item'  => string,      // 'picture' or 'file'
 *                      'postName' => string,   // $data['postData']
 *                      'id_user'  => int))
 *
 */
include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserAddItem extends ActionUserFileUploadBase
{
    /**
     * add user picture or file
     *
     * @param array $data
     * @return int user id or false on error
     */
    public function perform( $data = FALSE )
    { 
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $data['postData']['name']);

        if(FALSE == $this->moveUploadedFile($data['postData']['tmp_name'], $file_info['file_path']))
        { 
            $data['error'][] = 'File upload failed';
            return FALSE;
        }

        // set table name and item reference
        if($data['item'] == 'picture')
        {
            if(FALSE == $this->isAllowedImageExtension( $data ))
            {
                $data['error'][] = 'This file type isnt allowed to upload';
                return;
            }         
            $this->addPicture($data, $media_folder, $file_info);
        }
        else
        {
            if(FALSE == $this->isAllowedExtension( $data ))
            {
                $data['error'][] = 'This file type isnt allowed to upload';
                return;
            }          
            $this->addFile($data, $media_folder, $file_info);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new JapaModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' var isnt from type array!");
        }
        
        // check if user exists
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new JapaModelException ('"post_name" must be defined in view class'); 
        }
        elseif( !file_exists($data['postData']['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
            return FALSE;
        }    

        if(!isset($data['item']))
        {
            throw new JapaModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new JapaModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_user']))
        {
            throw new JapaModelException("No 'id_user' defined");
        }
        elseif(!is_int($data['id_user']))
        {
            throw new JapaModelException("'id_user' isnt numeric");
        }  
        elseif(($data['item'] == 'file') && ($this->config['user']['file_size_max'] <= filesize($data['postData']['tmp_name'])))
        {
            $data['error'][] = "Max file size allowed: {$this->config['user']['file_size_max']} bytes";
        }
        elseif(($data['item'] == 'picture') && ($this->config['user']['img_size_max'] <= filesize($data['postData']['tmp_name'])))
        {
            $data['error'][] = "Max picture size allowed: {$this->config['user']['img_size_max']} bytes";
        }
        
        if(count($data['error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    /**
     * add user file db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */   
    private function addFile( &$data, &$media_folder, &$file_info )
    {
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_file' );

        $_file = JAPA_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        
        // get mime type
        $type = '';
        if (function_exists('mime_content_type')) 
        {
            $type = mime_content_type($_file);
        }
        else
        {
            $type = $this->getMime( $file_info['file_name'] );
        }

        $file_size = filesize($_file);
       
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_file
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$file_size},
                    '{$type}' )";

        $this->model->dba->query($sql);     
    }
    /**
     * add user picture db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */       
    private function addPicture( &$data, &$media_folder, &$file_info )
    {
        $image_source = JAPA_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = JAPA_BASE_DIR . "data/user/" . $media_folder . '/thumb';
        
        // get image width and height
        if(FALSE !== ($info = getimagesize( $image_source )))
        {
            $img_width  = $info[0];
            $img_height = $info[1];
        }
        else
        {
            $img_width  = 0;
            $img_height = 0;
        }

        $pic_info = array();
        
        $this->model->action('common','imageThumb',
                             array('error'         => & $data['error'],
                                   'imgSource'     => (string)$image_source,
                                   'imgDestName'   => (string)$file_info['file_name'],
                                   'imgDestWidth'  => (int)$this->config['user']['thumb_width'],
                                   'imgDestFolder' => (string)$image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_pic' );
                
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_pic
                   (id_user,rank,file,size,mime,height,width)
                  VALUES
                   ({$data['id_user']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$pic_info['size']},
                    '{$pic_info['mime']}',
                    '{$img_height}',
                    '{$img_width}')";

        $this->model->dba->query($sql);                                       
    }
    
    /**
     * get new last item rank
     *
     * @param int $id_user User ID
     * @return int Rank number
     */    
    private function getNewLastRank( $id_user, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}{$table}  
            WHERE
                id_user='$id_user'
            ORDER BY 
                rank DESC
            LIMIT 1";
        
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() == 1)
        {
            $row = $stmt->fetchAssoc();
            return ++$row['rank'];
        }
        return 1;    
    } 
     
    /**
     * get_mime
     *
     * Get the mime type of a file. A file type is identified by its extension
     *
     * @param string $file file name
     * @return string Mime type
     */    
    private function getMime( &$file )
    {
        include_once(JAPA_BASE_DIR.'modules/common/includes/JapaCommonFileMime.php');
        return JapaCommonFileMime::getMime($file);
    }  
    /**
     * check if the file type to upload is allowed
     *
     * @param param $array
     * @return bool
     */       
    private function isAllowedExtension( &$data )
    {
        if(preg_match("/(\.[^.]+)$/i",$data['postData']['name'],$file_ext))
        {
            $disallowed_ext = explode(",",$this->config['rejected_files']);
            foreach($disallowed_ext as $ext)
            {
                $t = "/".trim($ext)."/i";
                if(preg_match($t,$file_ext[1]))
                {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }  
    
    /**
     * check if the file type to upload is allowed
     *
     * @param param $array
     * @return bool
     */       
    private function isAllowedImageExtension( &$data )
    {
        if(preg_match("/\.(gif|jpg|png)$/i",$data['postData']['name']))
        {
            return TRUE;
        }
        
        return FALSE;
    }       
}

?>

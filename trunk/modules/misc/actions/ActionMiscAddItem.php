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
 * ActionMiscAddItem class 
 *
 * $model->action('misc','addItem',
 *                array('error' => & array(),
 *                      'item'  => string,      // 'picture' or 'file'
 *                      'postData' => string,   // $_FILES[$data['postData']]
 *                      'id_text'  => int))
 *
 */
include_once(JAPA_BASE_DIR . 'modules/misc/includes/ActionMiscFileUploadBase.php');

class ActionMiscAddItem extends ActionMiscFileUploadBase
{
    /**
     * add Misc picture or file
     *
     * @param array $data
     * @return int Misc id or false on error
     */
    function perform( $data = FALSE )
    { 
        $media_folder = $this->getMiscMediaFolder( $data['id_text'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $data['postData']['name']);

        if(FALSE == $this->moveUploadedFile($data['postData']['tmp_name'], $file_info['file_path']))
        { 
            throw new JapaModelException ('Cant upload file');   
        }
        
        // set table name and item reference
        if($data['item'] == 'picture')
        {
            $this->addPicture($data, $media_folder, $file_info);
        }
        else
        {
            $this->addFile($data, $media_folder, $file_info);
        }
        
        return TRUE;
    }
    
    /**
     * validate Misc data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new JapaModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' var isnt from type array!");
        }
        
        // check if postName exists
        if( !isset($data['postData']) || empty($data['postData']) )
        {        
            throw new JapaModelException ('"postData" must be defined in view class'); 
        }
        // validate postName name
        elseif( !isset($data['postData']) )
        {
            $data['error'][] = 'You have to select a local file to upload';
        }    
        elseif( !file_exists($data['postData']['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
        }  

        // set table name and item reference
        if($data['item'] == 'picture')
        {
            if(FALSE == $this->isAllowedImageExtension( $data ))
            {
                $data['error'][] = 'This file type isnt allowed to upload';
                return FALSE;
            }         
        }
        else
        {
            if(FALSE == $this->isAllowedExtension( $data ))
            {
                $data['error'][] = 'This file type isnt allowed to upload';
                return FALSE;
            }          
        }   
        
        if(!isset($data['item']))
        {
            throw new JapaModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new JapaModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_text']))
        {
            throw new JapaModelException("No 'id_text' defined");
        }
        elseif(!is_int($data['id_text']))
        {
            throw new JapaModelException("'id_text' isnt from type int");
        }  
        elseif(($data['item'] == 'file') && ($this->config->getModuleVar('misc','file_size_max') <= filesize($data['postData']['tmp_name'])))
        {
            $data['error'][] = "Max file size allowed: {$this->config->getModuleVar('misc','file_size_max')} bytes";
        }
        elseif(($data['item'] == 'picture') && ($this->config->getModuleVar('misc','img_size_max') <= filesize($data['postData']['tmp_name'])))
        {
            $data['error'][] = "Max picture size allowed: {$this->config->getModuleVar('misc','img_size_max')} bytes";
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
        $rank = $this->getNewLastRank( $data['id_text'], 'misc_text_file' );

        $_file = JAPA_BASE_DIR . "data/misc/" . $media_folder . '/' . $file_info['file_name'];
        
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
       
        $sql = "INSERT INTO {$this->config->dbTablePrefix}misc_text_file
                   (id_text,rank,file,size,mime)
                  VALUES
                   ({$data['id_text']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$file_size},
                    '{$type}' )";

        $this->model->dba->query($sql);     
    }
    /**
     * add Misc picture db reference
     *
     * @param array $data action data array
     * @param string &$media_folder
     * @param array &$file_info
     */       
    private function addPicture( &$data, &$media_folder, &$file_info )
    {
        $image_source = JAPA_BASE_DIR . "data/misc/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = JAPA_BASE_DIR . "data/misc/" . $media_folder . '/thumb';
        
        $pic_info = array();
        
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
        
        $this->model->action('common','imageThumb',
                             array('error'         => & $data['error'],
                                   'imgSource'     => (string)$image_source,
                                   'imgDestName'   => (string)$file_info['file_name'],
                                   'imgDestWidth'  => (int)$this->config->getModuleVar('misc','thumb_width'),
                                   'imgDestFolder' => (string)$image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_text'], 'misc_text_pic' );
                
        $sql = "INSERT INTO {$this->config->dbTablePrefix}misc_text_pic
                   (id_text,rank,file,size,mime,width,height)
                  VALUES
                   ({$data['id_text']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$pic_info['size']},
                    '{$pic_info['mime']}',
                    {$img_width},
                    {$img_height})";

        $this->model->dba->query($sql);                                       
    }
    
    /**
     * get new last item rank
     *
     * @param int $id_text
     * @param string $table db table name
     * @return int Rank number
     */    
    function getNewLastRank( $id_text, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config->dbTablePrefix}{$table}  
            WHERE
                id_text={$id_text}
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
        include_once(JAPA_MODULES_DIR.'common/includes/JapaCommonFileMime.php');
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
            $disallowed_ext = explode(",",$this->config->getModuleVar('common','rejected_files'));

            foreach($disallowed_ext as $ext)
            {
                $t = "/".trim($ext)."/i";

                if(preg_match($t,$file_ext[1]))
                {var_dump($disallowed_ext);exit;
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

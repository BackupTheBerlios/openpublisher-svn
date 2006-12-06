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
 * ActionmiscFileUpload class 
 * Some misc action classes may extends this class
 *
 */

class ActionMiscFileUploadBase extends JapaAction
{
    /**
     * move_uploaded_file
     *
     * Move an uploaded file
     *
     * @param string $source Source file
     * @param string $destination Destination file
     * @return bool FALSE if it fails else TRUE
     */    
    protected function moveUploadedFile( $source, $destination)
    {
        if(FALSE == @move_uploaded_file($source, $destination))
        {
            if(FALSE == $this->isUploadedFile($source))
            {
                return FALSE;
            } 
            
            return FALSE;
        }
        
        // set media file rights
        if(!chmod($destination, $this->config->getVar('media_file_rights')))
        {
            trigger_error("Couldnt change file rights: ".$destination, E_USER_ERROR);
        }      
        return TRUE;
    }
    
    /**
     * is_file
     *
     * @param string $file File
     * @return bool True on success else false
     */    
    private function isUploadedFile($file)
    {
        clearstatcache();
        return @is_uploaded_file($file);
    }   
    
    protected function getMiscMediaFolder( $id_text )
    {
        $sql = "SELECT 
                    `media_folder` 
                FROM 
                    {$this->config->dbTablePrefix}misc_text
                WHERE
                    `id_text`={$id_text}";
                  
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() > 0)
        {
            $row = $stmt->fetchAssoc();

            if(empty($row['media_folder']))
            {
                return $this->createMiscMediaFolder( $id_text );
            }
            else
            {
                return $row['media_folder'];
            }
        }      
        return FALSE;
    }
    
    private function createMiscMediaFolder( $id_text )
    {
        // create unique folder that dosent exists       
        do
        {
            $folder = JapaCommonUtil::unique_crc32();
        }
        while(@is_dir(JAPA_BASE_DIR . 'data/misc/' . $folder));
        
        if(!mkdir(JAPA_BASE_DIR . 'data/misc/' . $folder, $this->config->getVar('media_folder_rights')))
        {
            throw new JapaModelException('Cant create media folder: ' . $folder);
        }

        if(!mkdir(JAPA_BASE_DIR . 'data/misc/' . $folder . '/thumb', $this->config->getVar('media_folder_rights')))
        {
            throw new JapaModelException('Cant create media folder: ' . $folder . '/thumb');
        }

        $this->model->action('misc','updateText',
                             array('error'   => & $data['error'],
                                   'id_text' => $id_text,
                                   'fields'  => array('media_folder' => $folder)));
        
        return $folder;
    }
    
    protected function getUniqueMediaFileName($media_folder, $file_name)
    {
        $result = array();
        $x = 0;
        
        do
        {
            if($x != 0)
            {
                $prefix = (string)$x . '_';
            }
            else
            {
                $prefix = '';
            }
            
            $result['file_path'] = JAPA_BASE_DIR . 'data/misc/' . $media_folder . '/' . $prefix . $file_name;
            $x++;
        }
        while(file_exists( $result['file_path'] )); 
        
        $result['file_name'] = $prefix . $file_name;
        
        return $result;
    }     
    public function perform( $data = FALSE ){}      
    public function validate( $data = FALSE )
    {
        return true;
    }               
}

?>

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
 * ActionUserUploadLogo class 
 *
 */
include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserUploadLogo extends ActionUserFileUploadBase
{
    /**
     * add user logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $data['postData']['name']);

        if(FALSE == $this->moveUploadedFile($data['postData']['tmp_name'], $file_info['file_path']))
        { 
            throw new JapaModelException ('Cant upload file');   
        }
        
        $error = array();
        $this->model->action('user','update',
                             array('error'   => & $error,
                                   'id_user' => (int)$data['id_user'],
                                   'fields'  => array('logo' => $file_info['file_name'])));
   
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
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' var isnt from type array!");
        }    
        
        
        if( (false == $data['postData']) || empty($data['postData']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }    
        elseif( !file_exists($data['postData']['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
            return FALSE;
        }   
        
        if(!is_int($data['id_user']))
        {
            throw new SmartModelException('Wrong id_user format');        
        }  
        
        if(FALSE == $this->isAllowedExtension( $data ))
        {
            $data['error'][] = 'This file type isnt allowed to upload';
            return FALSE;
        }         
        return TRUE;
    }
    
    /**
     * check if the file type to upload is allowed
     *
     * @param param $array
     * @return bool
     */       
    private function isAllowedExtension( &$data )
    {
        if(preg_match("/\.(gif|jpg|png)$/i",$data['postData']['name']))
        {
            return TRUE;
        }
        
        return FALSE;
    }       
}

?>
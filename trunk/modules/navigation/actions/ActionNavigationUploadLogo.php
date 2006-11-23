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
 * ActionNavigationUploadLogo class 
 *
 */
include_once(JAPA_MODULES_DIR . 'navigation/includes/ActionNavigationFileUploadBase.php');

class ActionNavigationUploadLogo extends ActionNavigationFileUploadBase
{
    /**
     * add user logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getNodeMediaFolder( $data['id_node'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $data['postData']['name']);

        if(FALSE == $this->moveUploadedFile($data['postData']['tmp_name'], $file_info['file_path']))
        { 
            throw new JapaModelException ('Cant upload file');   
        }
        
        $this->model->action('navigation',
                             'updateNode',
                             array('error'   => & $data['error'],
                                   'id_node' => $data['id_node'],
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
            throw new JapaModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' var isnt from type array!");
        }        
        // validate logo upload name
        if( !isset($data['postData']) || empty($data['postData']) )
        {        
            throw new JapaModelException ('"post_name" must be defined in view class'); 
        }     
        elseif( !file_exists($data['postData']['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
        }  
        
        if(!isset($data['id_node']))
        {
            throw new JapaModelException("No 'id_node' defined. Required!");
        }        
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }        

        if(FALSE == $this->isAllowedExtension( $data ))
        {
            $data['error'][] = 'This file type isnt allowed to upload';
        } 

        if(count($data['error']) > 0)
        {
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
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
 * ActionArticleUploadLogo class 
 *
 */
include_once(JAPA_BASE_DIR . 'modules/article/includes/ActionArticleFileUploadBase.php');

class ActionArticleUploadLogo extends ActionArticleFileUploadBase
{
    /**
     * add article logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getArticleMediaFolder( $data['id_article'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $data['postData']['name']);

        if(FALSE == $this->moveUploadedFile($data['postData']['tmp_name'], $file_info['file_path']))
        { 
            throw new JapaModelException ('Cant upload file');   
        }
        
        $this->model->action('article','updateArticle',
                             array('error'      => & $data['error'],
                                   'id_article' => $data['id_article'],
                                   'fields'     => array('logo' => $file_info['file_name'])));

           
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

        // validate logo upload file name
        if( !isset($data['postData']) || empty($data['postData']) )
        {        
            throw new JapaModelException ('"postData" must be defined in view class'); 
        }  
        elseif( !file_exists($data['postData']['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
        }  

        if(FALSE == $this->isAllowedExtension( $data ))
        {
            $data['error'][] = 'This file type isnt allowed to upload';
        } 
        
        if(!isset($data['id_article']))
        {
            throw new JapaModelException("No 'id_article' defined. Required!");
        }        
        if(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
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
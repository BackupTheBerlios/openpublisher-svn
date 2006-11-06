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
 * ActionArticleDeleteItem class 
 *
 * USAGE:
 *
 * $model->action('article','deleteItem',
 *                array('id_pic'  => int, // one of both param
 *                      'id_file' => int))
 */

class ActionArticleDeleteItem extends JapaAction
{
    /**
     * Delete node picture or file
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
        else
        {
            $this->deletePicture($data);
        }
        
        $this->removeEmptyDirectory( $data );
  
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
        
        if(isset($data['id_file']) && !is_int($data['id_file']))
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

        $this->model->action('article','getPicture',
                             array('result' => & $pic,
                                   'id_pic' => (int)$data['id_pic'],
                                   'error'  => & $data['error'],
                                   'fields' => array('file','id_article')));   

        $article = array();

        $this->model->action('article','getArticle',
                             array('result'     => & $article,
                                   'id_article' => (int)$pic['id_article'],
                                   'error'      => & $data['error'],
                                   'fields'     => array('media_folder')));   

        $this->idArticle   = $pic['id_article'];
        $this->mediaFolder = &$article['media_folder'];

        if(!@unlink(JAPA_BASE_DIR . 'data/article/'.$article['media_folder'].'/'.$pic['file']))
        {
           trigger_error('Cant delete article logo: data/article/'.$article['media_folder'].'/'.$pic['file'], E_USER_WARNING);
        }
        if(!@unlink(JAPA_BASE_DIR . 'data/article/'.$article['media_folder'].'/thumb/'.$pic['file']))
        {
           trigger_error('Cant delete article logo: data/article/'.$article['media_folder'].'/thumb/'.$pic['file'], E_USER_WARNING);
        }  
        
        // remove picture reference from database
        $this->model->action('article','updatePicture',
                             array('action'     => 'delete',
                                   'error'      => & $data['error'],
                                   'id_pic'     => (int)$data['id_pic'],
                                   'id_article' => (int)$pic['id_article']));    
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

        $this->model->action('article','getFile',
                             array('result'  => & $file,
                                   'error'   => & $data['error'],
                                   'id_file' => (int)$data['id_file'],
                                   'fields'  => array('file','id_article')));   

        $article = array();

        $this->model->action('article','getArticle',
                             array('result'     => & $article,
                                   'error'      => & $data['error'],
                                   'id_article' => (int)$file['id_article'],
                                   'fields'     => array('media_folder')));   

        $this->idArticle   = $file['id_article'];
        $this->mediaFolder = &$article['media_folder'];

        if(!@unlink(JAPA_BASE_DIR . 'data/article/'.$article['media_folder'].'/'.$file['file']))
        {
           trigger_error('Cant delete user logo: data/article/'.$article['media_folder'].'/'.$file['file'], E_USER_WARNING);
        }   
        // remove file reference from database
        $this->model->action('article','updateFile',
                             array('action'     => 'delete',
                                   'error'      => & $data['error'],
                                   'id_file'    => (int)$data['id_file'],
                                   'id_article' => (int)$file['id_article']));    
    }
    /**
     * remove empty user directory
     *
     * @return bool
     */  
    private function removeEmptyDirectory( &$data )
    {
        $dir = JAPA_BASE_DIR . 'data/article/' . $this->mediaFolder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'article','updateArticle',
                                  array('id_article' => (int)$this->idArticle,
                                        'error'      => & $data['error'],
                                        'fields'     => array('media_folder' => '')) );
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
                if ( ( $file == "." ) || ( $file == ".." ) || is_dir($dir . '/' . $file) )
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
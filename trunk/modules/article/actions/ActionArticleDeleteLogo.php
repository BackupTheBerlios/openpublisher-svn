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
 * ActionArticleDeleteLogo class 
 *
 * USAGE:
 * 
 * $model->action('article','deleteLogo',
 *                array('id_article' => int))
 */

class ActionArticleDeleteLogo extends JapaAction
{
    /**
     * Delete node logo
     *
     * param:
     * data['id_article']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $article = array();

        $this->model->action('article','getArticle',
                             array('result'     => & $article,
                                   'error'      => & $data['error'],
                                   'id_article' => (int)$data['id_article'],
                                   'fields'     => array('logo','media_folder')));   

        if(!@unlink(JAPA_BASE_DIR . 'data/article/'.$article['media_folder'].'/'.$article['logo']))
        {
            throw new JapaModelException('Cant delete user logo: data/article/'.$article['media_folder'].'/'.$article['logo']);
        }
                            
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$data['id_article'],
                                   'error'      => & $data['error'],
                                   'fields'     => array('logo' => '')));

        $this->removeEmptyDirectory( $article['media_folder'], $data );
        
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
        if(!isset($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt defined');        
        }    
        elseif(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
        }
        
        return TRUE;
    }
    
    /**
     * remove empty user directory
     *
     */  
    private function removeEmptyDirectory( &$media_folder, &$data )
    {
        $dir = JAPA_BASE_DIR . 'data/article/' . $media_folder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            JapaCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'article','updateArticle',
                                  array('id_article' => (int)$data['id_article'],
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
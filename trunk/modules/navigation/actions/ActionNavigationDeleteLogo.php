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
 * ActionNavigationDeleteLogo class 
 *
 * USAGE:
 * 
 * $model->action('navigation','deleteLogo',
 *                array('id_node' => int))
 */

class ActionNavigationDeleteLogo extends JapaAction
{
    /**
     * Delete node logo
     *
     * param:
     * data['id_node']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $_file = '';

        $this->model->action('navigation','getNode',
                             array('result'  => & $_file,
                                   'id_node' => (int)$data['id_node'],
                                   'fields'  => array('logo','media_folder')));   

        if(!@unlink(JAPA_BASE_DIR . 'data/navigation/'.$_file['media_folder'].'/'.$_file['logo']))
        {
            throw new SmartModelException('Cant delete user logo: data/navigation/'.$_file['media_folder'].'/'.$_file['logo']);
        }
                            
        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$data['id_node'],
                                   'fields'  => array('logo' => '')));
        
        $this->removeEmptyDirectory( $data['id_node'], $_file['media_folder'] );                           
        
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
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }    
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        
        return TRUE;
    }
    /**
     * remove empty navigation data directory
     *
     * @return bool
     */  
    private function removeEmptyDirectory( $id_node, &$media_folder )
    {
        $dir = JAPA_BASE_DIR . 'data/navigation/' . $media_folder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'navigation','updateNode',
                                  array('id_node' => (int)$id_node,
                                        'fields'  => array('media_folder' => '')) );
        }
    }
    /**
     * check if data directory is empty
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
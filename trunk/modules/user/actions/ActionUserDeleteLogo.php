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
 * ActionUserDeleteLogo class 
 *
 *
 * USAGE:
 * 
 * $model->action('user','deleteLogo',
 *                array('id_user' => int))
 * 
 */

class ActionUserDeleteLogo extends SmartAction
{
    /**
     * Delete user logo
     *
     * param:
     * data['id_user']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $_file = array();

        $this->model->action('user','getUser',
                             array('result'  => & $_file,
                                   'id_user' => (int)$data['id_user'],
                                   'fields'  => array('logo','media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$_file['media_folder'].'/'.$_file['logo']))
        {
            throw new SmartModelException('Cant delete user logo: data/user/'.$_file['media_folder'].'/'.$_file['logo']);
        }
        
        $this->error = array();
        $this->model->action('user','update',
                             array('error'   => & $this->error,
                                   'id_user' => (int)$data['id_user'],
                                   'fields'  => array('logo' => '')));

        $this->removeEmptyDirectory( $_file['media_folder'], $data );
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if(!is_int($data['id_user']))
        {
            throw new SmartModelException('Wrong "id_user" format: ');        
        }
        
        if(FALSE == $this->userExists( $data['id_user'] ))
        {
            throw new SmartModelException('id_user dosent exists: '.$data['id_user']);  
        }
        
        return TRUE;
    }
    
    /**
     * check if id_user exists
     *
     * @param int $id_user User id
     * @return bool
     */    
    private function userExists( $id_user )
    {  
        $sql = "
            SELECT
                id_user
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                id_user=$id_user";
        
        $result = $this->model->dba->query($sql);

        if($result->numRows() == 1)
        {
            return TRUE;
        }
        
        return FALSE;    
    } 
    
    /**
     * remove empty user directory
     *
     */  
    private function removeEmptyDirectory( &$media_folder, &$data )
    {
        $dir = SMART_BASE_DIR . 'data/user/' . $media_folder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'user','update',
                                  array('id_user' => (int)$data['id_user'],
                                        'error'   => & $this->error,
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
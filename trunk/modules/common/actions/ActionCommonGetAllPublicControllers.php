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
 * ActionCommonGetAllPublicControllers
 *
 * USAGE:
 * $model->action( 'common','getAllPublicControllers',
 *                 array('result' => & array );   
 *
 */

class ActionCommonGetAllPublicControllers extends JapaAction
{
    /**
     * Perform on the action call
     *
     * find all public view classe php files of the folder views_xxx/
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $controller_name = array();
        $controller_dir  = JAPA_APPLICATION_DIR . 'controllers/' . $this->model->config['views_folder'];
          
        if ( (($handle = @opendir( $controller_dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if(preg_match("/^Controller([a-zA-z0-9_]+)\.php$/", $file, $name))
                {
                    $controller_name[] = $name[1];
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open controllers folder to read: " . $controller_dir, E_USER_ERROR  );
        }
        
        sort( $controller_name );
        
        $view_dir = JAPA_PUBLIC_DIR . 'views/' . $this->model->config['views_folder'];
        
        $data['result'] = array();
        
        foreach($controller_name as $name)
        {
            if(file_exists($view_dir . '/view.' . $name . '.php'))
            {
                $data['result'][] = array('name' => $name,
                                          'view'  => TRUE);
            }
            else
            {
                $data['result'][] = array('name' => $name,
                                          'view'  => FALSE);            
            }
        }   
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        // The Exception catch has to react
        //
        if( !isset($data['result']) )
        {
            throw new JapaModelException("No 'result' array var defined");
        }
        
        return true;
    }    
}

?>
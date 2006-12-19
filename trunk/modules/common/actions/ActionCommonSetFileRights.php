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
 * Test action (simple example)
 *
 */

class ActionCommonSetFileRights extends JapaAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        // Here we assign a variable with some content
        // The action caller has to evaluate its content
        //
       //$this->chmod_R(JAPA_BASE_DIR . 'modules/common/includes/media', 0777); 
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        return TRUE;
    } 
    
    /**
     * delete_dir_tree
     *
     * Delete directory and content recursive
     *
     * @param string $dir Directory
     */
    private function chmod_R($path, $filemode) 
    {
        if (!is_dir($path))
        {
            return chmod($path, $filemode);
        }
            $dh = opendir($path);
            while ($file = readdir($dh)) 
            {
                if($file != '.' && $file != '..') 
                {
                    $fullpath = $path.'/'.$file;
                    if(!is_dir($fullpath)) 
                    {
                        if (!chmod($fullpath, $filemode))
                          return FALSE;
                    } 
                    else 
                    {
                        if (!$this->chmod_R($fullpath, $filemode))
                          return FALSE;
                    }
                }
           }
 
           closedir($dh);
  
           if(chmod($path, $filemode))
             return TRUE;
           else
             return FALSE;
    }
}

?>
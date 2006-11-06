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
 * ActionOptionsDeletePublicCache class 
 *
 */
 
class ActionOptionsDeletePublicCache extends JapaAction
{
   /**
     * delete all cached files
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        $cache_dir = JAPA_BASE_DIR . 'cache' ;
          
        if ( (($handle = @opendir( $cache_dir ))) != FALSE )
        {
            while ( (( $_file = readdir( $handle ) )) != false )
            {
                if ( ( $_file == "." ) || ( $_file == ".." ) || ($_file == '.htaccess') )
                {
                    continue;
                }
                
                $cache_file = $cache_dir .'/'. $_file;
                if(is_file($cache_file))
                {
                    if(!@unlink ($cache_file))
                    {
                        trigger_error( "Can not delete cache file: ".$cache_file, E_USER_WARNING  );
                    }
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open folder to read: ".$cache_dir, E_USER_WARNING  );
        }
        return TRUE;
    } 
}

?>

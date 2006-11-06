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
 * Util class 
 *
 */
 
class SmartCommonUtil
{
    /**
     * Add slashes if magic_quotes are disabled
     *
     * @return string base location
     */ 
    public static function addSlashes( $var )
    {
        if ( JAPA_MAGIC_QUOTES == 0 )
        {   
            return addslashes($var);
        }
        else
        {
            return $var;
        }
    }
    
    /**
     * Add slashes if magic_quotes are disabled
     *
     * @return string base location
     */ 
    public static function stripSlashes( $var )
    {
        if ( JAPA_MAGIC_QUOTES == 0 )
        {   
            return $var;
        }
        else
        {
            return stripslashes($var);
        }
    }     

    /**
     * Make unique md5_string
     *
     * @return string unique md5 string 
     */
    public static function unique_md5_str()
    {
        mt_srand((double) microtime()*1000000);
        return md5(str_replace(".","",$_SERVER["REMOTE_ADDR"]) + mt_rand(100000,999999)+uniqid(microtime()));    
    } 

    /**
     * Make unique crc32
     *
     * @return int unique crc32  
     */
    public static function unique_crc32()
    {
        mt_srand((double) microtime()*1000000);
        return crc32(str_replace(".","",$_SERVER["REMOTE_ADDR"]) + mt_rand(100000,999999)+uniqid(microtime()));    
    }
    
    /**
     * delete_dir_tree
     *
     * Delete directory and content recursive
     *
     * @param string $dir Directory
     */
    public static function deleteDirTree( $dir )
    {
        if ( (($handle = @opendir( $dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if ( @is_dir( $dir . '/' . $file ) )
                {
                    SmartCommonUtil::deleteDirTree( $dir . '/' . $file );
                }
                else
                {
                    if( (@unlink( $dir . '/' . $file )) == FALSE )
                    {
                        trigger_error( "Can not delete content in dir tree: {$dir}/{$file}", E_USER_ERROR  );
                    }
                }
            }
            @closedir( $handle );
            if( (@rmdir( $dir )) == FALSE )
            {
                trigger_error( "Can not remvoe dir: {$dir}", E_USER_ERROR  );
            }
        }
        else
        {
            trigger_error( "Can not delete content dir: {$dir}", E_USER_ERROR  );
        }
    } 
    /**
     * Returns the correct link for the back/pages/next links
     *
     * @return string Url
     */
    public static function getQueryString()
    {
        // Sort out query string to prevent messy urls
        $querystring = array();
        $qs = array();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $qs = explode('&', str_replace('&amp;', '&', $_SERVER['QUERY_STRING']));
            for ($i=0, $cnt=count($qs); $i<$cnt; $i++) {
                list($name, $value) = explode('=', $qs[$i]);
                $qs[$name] = $value;
                unset($qs[$i]);
            }
        }

        foreach ($qs as $name => $value) {
            $querystring[] = $name . '=' . $value;
        }

        return '?' . implode('&', $querystring);
    }     
}

?>
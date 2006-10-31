<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smart file cache class
 *
 *
 */
 
class SmartFileViewCache extends SmartCache
{
    /**
     * Cache file (full path)
     *
     * @var string $cacheFile
     */
    private $cacheFile;
    /**
     * Cache Time file (full path)
     *
     * @var string $cacheTimeFile
     */
    private $cacheTimeFile;
    /**
     * Temporary Cache file (full path)
     *
     * @var string $cacheFileTmp
     */
    private $cacheFileTmp;
    /**
     * Temporary Cache Time file (full path)
     *
     * @var string $cacheTimeFileTmp
     */
    private $cacheTimeFileTmp;    
    /**
     * File pointer
     *
     * @var resource $cachefp
     */
    private $cachefp;    
    /**
     * Time File pointer
     *
     * @var resource $cacheTimefp
     */
    private $cacheTimefp; 
    
    /**
     * Check if cache id exists
     *
     * @param int $expire expire time stamp
     * @param int $viewId cache id
     */   
    public function cacheIdExists( $expire, & $viewId )
    {
        if($this->config['cache_time_type'] == 'filemtime')
        {
            return $this->_filemtime( $expire, $viewId );
        }
        elseif($this->config['cache_time_type'] == 'filestime')
        {
            return $this->_filestime( $expire, $viewId );
        }
    }

    /**
     * fetch cache content using php filemtime function
     *
     * @param int $expire expire time stamp
     * @param int $viewId cache id
     */     
    public function _filemtime( $expire, & $viewId )
    {
        $this->cacheFile = $this->config['cache_path'].md5($viewId);

        if( file_exists($this->cacheFile)  )
        {
            $cachetime = filemtime($this->cacheFile);

            if( ($cachetime != FALSE) && ((time() - $expire) < $cachetime)  )
            {
                include($this->cacheFile);
                return TRUE;
            } 
        }    
                      
        $this->cacheFileTmp = $this->cacheFile.'.'.getmypid();
        $this->cachefp = fopen($this->cacheFileTmp, 'w');
    } 

    /**
     * fetch cache content using a separate file that contains the cache time
     *
     * @param int $expire expire time stamp
     * @param int $viewId cache id
     */        
    public function _filestime( $expire, & $viewId )
    {
        $this->cacheFile = $this->config['cache_path'].md5($viewId);
        $this->cacheTimeFile = $this->cacheFile . 'time';

        if( file_exists($this->cacheTimeFile)  )
        {
            $cachetime = file_get_contents($this->cacheTimeFile);

            if( ($cachetime != FALSE) && ((time() - $expire) < intval($cachetime))  )
            {
                include($this->cacheFile);
                return TRUE;
            } 
        }    
                
        $this->cacheTimeFileTmp = $this->cacheTimeFile.'.'.getmypid();
        $this->cacheTimefp = fopen($this->cacheTimeFileTmp, 'w');        
        $this->cacheFileTmp = $this->cacheFile.'.'.getmypid();
        $this->cachefp = fopen($this->cacheFileTmp, 'w');
    } 

    /**
     * Write content into a cache file
     *
     * @var string $content
     */     
    public function cacheWrite( & $content )
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        
        if($this->cacheTimefp)
        {
            fwrite($this->cacheTimefp, strval(time()));
            fclose($this->cacheTimefp);
            if( ($os === 'WIN') && file_exists($this->cacheTimeFile)  )
            {
                unlink($this->cacheTimeFile);
            }
            @rename($this->cacheTimeFileTmp, $this->cacheTimeFile);
        } 
        
        if($this->cachefp)
        {
            fwrite($this->cachefp, $content);
            fclose($this->cachefp);
            if( ($os === 'WIN') && file_exists($this->cacheFile)  )
            {
                unlink($this->cacheFile);
            }                
            @rename($this->cacheFileTmp, $this->cacheFile);        
        }
    }    
}

?>
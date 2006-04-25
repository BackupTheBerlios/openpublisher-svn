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
 * ActionCommonRssFetch
 *
 * USAGE:
 *                                                  
         $this->model->action('common','rssFetch',
                              array('url'        => (string),
                                    'result'     => & array,
                                    'encoding'   => (string),
                                    'cache_time' => (int) ));  

 *
 */

if(!defined('MAGPIE_DIR'))
{
    define('MAGPIE_DIR', SMART_BASE_DIR . 'modules/common/includes/magpierss/');
}
include_once(MAGPIE_DIR.'rss_fetch.inc');    

/**

 * 
 */
class ActionCommonRssFetch extends SmartAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        if(!defined('MAGPIE_OUTPUT_ENCODING'))
        {
            if(isset($data['encoding']))
            {
                define('MAGPIE_OUTPUT_ENCODING', $data['encoding']);
            }
        }

        if(isset($data['cache_time']))
        {
            define('MAGPIE_CACHE_ON',  TRUE);
            define('MAGPIE_CACHE_AGE', (int)$data['cache_time']);
            define('MAGPIE_CACHE_DIR', SMART_BASE_DIR . 'data/common/rss_cache');
        }        
        
        if(!$rss = fetch_rss( $data['url'] ))
        {
            trigger_error('Rss feed not available: '. magpie_error(), E_USER_WARNING);
            
            $data['result']['items']     = array();
            $data['result']['channel']   = array();
            $data['result']['image']     = array();
            $data['result']['textinput'] = array();   
            
            return;
        }
        
        $data['result']['items']     = $rss->items;
        $data['result']['channel']   = $rss->channel;
        $data['result']['image']     = $rss->image;
        $data['result']['textinput'] = $rss->textinput;  
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {
        if( !isset($data['url']) )
        {
            throw new SmartModelException('url var is required');        
        }
        if( !is_string($data['url']) )
        {
            throw new SmartModelException('url var isnt from type string');        
        }        

        if( isset($data['encoding']) )
        {
            if( !is_string($data['encoding']) )
            {
                throw new SmartModelException('encoding var isnt from type string');        
            }  
            if( empty($data['encoding']) )
            {
                throw new SmartModelException('encoding var is empty');        
            }               
        }
  
        if( isset($data['cache_time']) )
        {
            if( !is_int($data['cache_time']) )
            {
                throw new SmartModelException('cache_time var isnt from type int');        
            }                
        }      
        return TRUE;
    }    
}

?>
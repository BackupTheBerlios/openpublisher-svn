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
 * ActionCommonRssBuilder
 *
 */

include_once (SMART_BASE_DIR . 'modules/common/includes/rssbuilder/class.RSSBuilder.inc.php');

/**
 * 
 */
class ActionCommonRssBuilderInit extends SmartAction
{
    /**
     */
    public function perform( $data = FALSE )
    {
        /* create the object - remember, not all attibutes are supported by every rss version. just hand over an empty string if you don't need a specific attribute */
        if(isset($data['filename']))
        {
            $filename = $data['filename'];
        }
        else
        {
            $filename = FALSE;
        }
             
        if(!isset($data['rss']['encoding']))
        {
            $data['rss']['encoding'] = 'UTF-8';
        }
        $encoding =(string) $data['rss']['encoding'];      

        if(!isset($data['rss']['about']))
        {
            $data['rss']['about'] = '';
        } 
        $about = (string) $data['rss']['about'];

        if(!isset($data['rss']['title']))
        {
            $data['rss']['title'] = '';
        } 
        $title = (string) $data['rss']['title'];

        if(!isset($data['rss']['description']))
        {
            $data['rss']['description'] = '';
        } 
        $description = (string) $data['rss']['description'];
        
        if(!isset($data['rss']['image_link']))
        {
            $data['rss']['image_link'] = '';
        }        
        $image_link = (string) $data['rss']['image_link'];
        
        if(!isset($data['rss']['category']))
        {
            $data['rss']['category'] = '';
        }        
        $category = (string) $data['rss']['category']; // (only rss 2.0)
        
        if(!isset($data['rss']['cache']))
        {
            $data['rss']['cache'] = 0;
        }         
        $cache = (string) $data['rss']['cache']; // in minutes (only rss 2.0)
               
        $rssfile = new RSSBuilder($encoding, $about, $title, $description, $image_link, $category, $cache, $filename);

        /* if you want you can add additional Dublic Core data to the basic rss file (if rss version supports it) */
        if(!isset($data['dc']['publisher']))
        {
            $data['dc']['publisher'] = '';
        }
        $publisher = (string) $data['dc']['publisher']; // person, an organization, or a service

        if(!isset($data['dc']['creator']))
        {
            $data['dc']['creator'] = '';
        }
        $creator = (string) $data['dc']['creator']; // person, an organization, or a service

        $date = (string) time();
        
        if(!isset($data['dc']['language']))
        {
            $data['dc']['language'] = '';
        }        
        $language = (string) $data['dc']['language'];
        
        if(!isset($data['dc']['rights']))
        {
            $data['dc']['rights'] = '';
        }         
        $rights = (string) $data['dc']['rights'];
        
        if(!isset($data['dc']['coverage']))
        {
            $data['dc']['coverage'] = '';
        }        
        $coverage = (string) $data['dc']['coverage']; // spatial location , temporal period or jurisdiction
        
        if(!isset($data['dc']['contributor']))
        {
            $data['dc']['contributor'] = '';
        }         
        $contributor = (string) $data['dc']['contributor']; // person, an organization, or a service
        
        $rssfile->addDCdata($publisher, $creator, $date, $language, $rights, $coverage, $contributor);

        /* if you want you can add additional Syndication data to the basic rss file (if rss version supports it) */
        if(!isset($data['sydate']['period']))
        {
            $data['sydate']['period'] = '';
        }  
        $period = (string) $data['sydate']['period']; // hourly / daily / weekly / ...

        if(!isset($data['sydate']['frequency']))
        {
            $data['sydate']['frequency'] = 0;
        } 
        $frequency = (int) $data['sydate']['frequency']; // every X hours/days/...

        if(!isset($data['sydate']['base']))
        {
            $data['sydate']['base'] = 10000;
        } 
        $base = (string) time()-$data['sydate']['base'];
        $rssfile->addSYdata($period, $frequency, $base);

        return $rssfile;
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {

        return TRUE;
    }    
}

?>
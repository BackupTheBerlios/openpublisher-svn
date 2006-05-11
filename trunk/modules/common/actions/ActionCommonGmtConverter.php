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
 * ActionCommonGmtConverter
 *
 * USAGE:
        $this->model->action('common', 'gmtConverter',
                             array('action'   => string,  // 'gmtToDate' or 'dateToGmt'
                                   'timezone' => int,     // -12 to 12
                                   'date'     => & $_date // 1. &(string) eg. 2006-11-03 16:34:10
                                                          // 2. (array)  eg. array('year'   => &(string),
                                                          //                       'month'  => &(string),
                                                          //                       'day'    => &(string),
                                                          //                       'hour'   => &(string),
                                                          //                       'minute' => &(string)                             
                                   ));
 *
 */

class ActionCommonGmtConverter extends SmartAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        // if date is from type string eg. 20005-12-05 20:34:45
        if(is_string($data['date']))
        {
            $this->gmtToUserGmt( $data );
            return;
        }
        
        // get timezone
        if(!isset($data['timezone']))
        {
            if(isset($this->config['user_gmt']))
            {
                $timezone = 3600 * $this->config['user_gmt'];
            }
            elseif(isset($this->config['default_gmt']))
            {
                $timezone = 3600 * $this->config['default_gmt'];
            }
        }
        else
        {
            $timezone = 3600 * $data['timezone'];
        }
        
        $timestamp = $this->_Mktime( $data['date']['year'], 
                                     $data['date']['month'], 
                                     $data['date']['day'], 
                                     $data['date']['hour'], 
                                     $data['date']['minute']);
    
        if($data['action'] == "gmtToDate")
        {
            $this->gmtToDate( $data['date'], $timestamp, $timezone );
        }
        elseif($data['action'] == "dateToGmt")
        {
            $this->dateToGmt( $data['date'], $timestamp, $timezone );
        }
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['action']))
        {
            throw new SmartModelException("var 'action' isnt defined");
        }
        if(!is_string($data['action']))
        {
            throw new SmartModelException("var 'action' isnt from type string");
        }
        if(($data['action'] != "gmtToDate") && ($data['action'] != "dateToGmt"))
        {
            throw new SmartModelException("var 'action' has wrong value: 'gmtToDate' or 'dateToGmt'. Value: " . $data['action']);
        }  
        
        if(isset($data['timezone']))
        {
            if(!is_int($data['timezone']))
            {
                throw new SmartModelException("'timezone' isnt from type int");
            }
            if( ($data['timezone'] < -12) || ($data['timezone'] > 12) )
            {
                throw new SmartModelException("'timezone' value must be from -12 to +12. Value: " . $data['timezone']);
            }
        }
        
        if(!isset($data['date']))
        {
            throw new SmartModelException("array 'date' isnt defined");
        }
        
        // proceed to the perform methode
        if(is_string($data['date']))
        {
            return TRUE;
        }
        
        if(!is_array($data['date']))
        {
            throw new SmartModelException("var 'date' isnt from type array");
        }
        if(!isset($data['date']['year']))
        {
            throw new SmartModelException("array var 'date''year' isnt set");
        }
        if(!preg_match("/[0-9]{4}/",$data['date']['year']))
        {
            throw new SmartModelException("array var 'date''year' has wrong value");
        }
        if(!isset($data['date']['month']))
        {
            throw new SmartModelException("array var 'date''month' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['month']))
        {
            throw new SmartModelException("array var 'date''month' has wrong value");
        }   
        if(!isset($data['date']['day']))
        {
            throw new SmartModelException("array var 'date''day' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['day']))
        {
            throw new SmartModelException("array var 'date''day' has wrong value");
        } 
        if(!isset($data['date']['hour']))
        {
            throw new SmartModelException("array var 'date''hour' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['hour']))
        {
            throw new SmartModelException("array var 'date''hour' has wrong value");
        } 
        if(!isset($data['date']['minute']))
        {
            throw new SmartModelException("array var 'date''minute' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['minute']))
        {
            throw new SmartModelException("array var 'date''minute' has wrong value");
        } 
        
        return TRUE;
    }  

    private function gmtToDate( & $data, $timestamp, $timezone )
    {                            
        $this->_Date( $data, $timestamp, $timezone, "add" );
    }

    private function dateToGmt( & $data, $timestamp, $timezone )
    {                            
        $this->_Date( $data, $timestamp, $timezone, "sub" );
    }
    
    private function _Mktime( $year, $month, $day, $hour, $minute, $second = '0' )
    {
        return mktime( $hour, $minute, $second, $month, $day, $year );
    }
    
    private function _Date( & $data, $timestamp, $timezone, $cal )
    {  
        if($cal == "sub")
        {
            $timestamp = $timestamp - $timezone;
        }
        elseif($cal == "add")
        {
            $timestamp = $timestamp + $timezone;
        }
        
        $data['year']   = date( "Y", $timestamp );
        $data['month']  = date( "m", $timestamp );
        $data['day']    = date( "d", $timestamp );
        $data['hour']   = date( "H", $timestamp );
        $data['minute'] = date( "i", $timestamp );
    }
    
    /**
     * convert date string from gmt to defined gmt
     *
     * @param mixed $data
     */    
    private function gmtToUserGmt( & $data )
    {
        if(preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $data['date'], $tmp))
        {
            $_data = array('action'   => $data['action'],
                           'date'     => array('year'   => &$tmp[1],
                                               'month'  => &$tmp[2],
                                               'day'    => &$tmp[3],
                                               'hour'   => &$tmp[4],
                                               'minute' => &$tmp[5]) );
                                               
            if(isset($data['timezone']))
            {
                $_data['timezone'] = $data['timezone'];
            }
                                               
            // convert date from user timezone to gmt+0
            $this->model->action('common', 'gmtConverter', $_data); 
                                                     
            $data['date'] = $tmp[1].'-'.$tmp[2].'-'.$tmp[3].' '.$tmp[4].':'.$tmp[5].':'.$tmp[6];
        }
    }
}

?>
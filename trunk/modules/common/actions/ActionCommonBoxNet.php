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
 * ActionCommonFilterTrim
 *
 * USAGE:
 * $model->action( 'common', 'filterTrim', 
 *                 array('str' => & (string) );
 *
 */

class ActionCommonBoxNet extends JapaAction
{

    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {

        echo $this->model->session->get('boxSid');
        if($this->model->session->get('boxSid') == NULL)
        {
            $this->connect(  $data );
        }
        else
        {
            $this->boxSid      = $this->model->session->get( 'boxSid' );
            $this->boxAccessId = $this->model->session->get( 'boxAccessId' );
            $this->boxUserId   = $this->model->session->get( 'boxUserId' );
        }

        switch( $data['action'] )
        {
            case 'list':
                    return $this->_list( $data );
                break;
            default:
       
        }

    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {

        return TRUE;
    }  

    private function _list( & $data )
    {
        $header = array();
        $header[] = "Content-type: Content-Type: text/xml";
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        
        echo 'http://www.box.net/ping/'.$this->boxSid;
        curl_setopt($this->curl, CURLOPT_URL,'http://www.box.net/ping/'.$this->boxSid);
        $request = "<xml><action>account_tree</action><folder_id>0</folder_id><one_level>1</one_level></xml>";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
        $_data = curl_exec($this->curl); 
        
        if (curl_errno($this->curl)) 
        {
           trigger_error(curl_error($this->curl), E_USER_ERROR);
           exit;
        } 
        else
        {
           curl_close($this->curl);
           $xml  = simplexml_load_string($_data);

           echo "<pre>";var_dump($xml);echo "</pre>";
       } 
    }
    
    private function connect( & $data )
    {
        $header = array();
        $header[] = "Content-type: Content-Type: text/xml";
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 10);
        
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($this->curl, CURLOPT_URL,'http://www.box.net/ping');
        $request = "<xml><action>authorization</action><login>{$data['login']}</login><password>{$data['password']}</password></xml>";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
        $_data = curl_exec($this->curl); 
        
        if (curl_errno($this->curl)) 
        {
           trigger_error(curl_error($this->curl), E_USER_ERROR);
           exit;
        } 
        else
        {
           //curl_close($this->curl);
           $xml = simplexml_load_string($_data);

            if(trim($xml->status) == "logged")
            {
                $this->model->session->set('boxSid',      $xml->user->sid);
                $this->model->session->set('boxAccessId', $xml->user->access_id);
                $this->model->session->set('boxUserId',   $xml->user->user_id);
                
                $this->boxSid      = $xml->user->sid;
                $this->boxAccessId = $xml->user->access_id;
                $this->boxUserId   = $xml->user->user_id;  
            }
       } 
       curl_close($this->curl);
    }
}

?>
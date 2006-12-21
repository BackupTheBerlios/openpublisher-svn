<?php
/*
 * Created on 21.12.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
/**
 * View helper class ViewHelperMailto
 * 
 * Usage:
 * $html_email_code = $helper->escape(email  (string),
 *                                    text   (string), // if false it use the email
 *                                    escape_function (string) // 'simple' or 'javascript' or 'javascript_charcode'
 *                                   );
 *
 * 
 */
 
  class ViewHelperMailto extends JapaViewHelper
  {
  	private $email_string = '';
  	
  	public function perform()
  	{
  		if(!isset($this->args[0]))
  		{
  			return null;
  		}
  		if(!isset($this->args[1]))
  		{
  			$this->args[1] = $this->args[0];
  		}	
  		elseif($this->args[1] == false)
  		{
  			$this->args[1] = $this->args[0];
  		}	
  		if(!isset($this->args[2]))
  		{
  			$this->args[2] = 'simple';
  		}	
  		
  		$this->email_string = '';
  		
  		switch($this->args[2])
  		{
  			case 'simple':
  				$this->escape_simple();
  				break;
  			case 'javascript':
  				$this->escape_javascript();  
  				break;
  			case 'javascript_charcode':
  				$this->escape_javascript_charcode();  
  				break;	
  			default:
  				$this->escape_default(); 
  		}
  		
  		return $this->email_string;
  	}

    private function add_string( $value )
    {
    	$this->email_string .= $value;
    }

    private function escape_default()
    {
    	$this->add_string('<a href="mailto:');
    	$this->add_string( $this->args[0] );
        $this->add_string('">'.$this->args[1].'</a>');
    }
    private function escape_simple()
    {
    	$this->add_string('<a href="mailto:');
    	$this->add_string( str_replace("@","%40",$this->args[0]) );
        $this->add_string('">'.str_replace("@","%40",$this->args[1]).'</a>');
    }
    private function escape_javascript()
    {
        $string = 'document.write(\'<a href="mailto:'.$this->args[0].'">'.$this->args[1].'</a>\');';
        $js_encode = '';
        for ($x=0; $x < strlen($string); $x++) 
        {
            $js_encode .= '%' . bin2hex($string[$x]);
        }

        $this->add_string('<script type="text/javascript">eval(unescape(\''.$js_encode.'\'))</script>');
    }
    private function escape_javascript_charcode()
    {
        $html = '<a href="mailto:'.$this->args[0].'">'.$this->args[1].'</a>';
        
        $_ord     = array();
        $html_len = strlen($html);

        for($x = 0, $y = $html_len; $x < $y; $x++ )
        {
            $_ord[] = ord($html[$x]);   
        }

        $this->add_string("<script type=\"text/javascript\" language=\"javascript\">\n<!--\n");
        $this->add_string("{document.write(String.fromCharCode(");
        $this->add_string(implode(',',$_ord));
        $this->add_string("))}\n//-->\n</script>\n");
    }
  }
?>

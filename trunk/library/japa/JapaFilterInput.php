<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * JapaFilterInput
 * this is an exact copy of zend_filter_input except of the methode:
 * public function setSource( $name, $value )
 *
 *
 */
 
require_once 'Zend/Filter.php';
 
class JapaFilterInput
{
    private $_source = NULL;

    public function __construct(&$source = NULL, $strict = TRUE)
    {
        $this->_source = $source;

        if ($strict) {
            $source = NULL;
        }
    }

    /**
     * Returns only the alphabetic characters in value.
     *
     * @param mixed $key
     * @return mixed
     */
    public function getAlpha($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getAlpha($this->_source[$key]);
    }

    /**
     * Returns only the alphabetic characters and digits in value.
     *
     * @param mixed $key
     * @return mixed
     */
    public function getAlnum($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getAlnum($this->_source[$key]);
    }

    /**
     * Returns only the digits in value. This differs from getInt().
     *
     * @param mixed $key
     * @return mixed
     */
    public function getDigits($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getDigits($this->_source[$key]);
    }

    /**
     * Returns dirname(value).
     *
     * @param mixed $key
     * @return mixed
     */
    public function getDir($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getDir($this->_source[$key]);
    }

    /**
     * Returns (int) value.
     *
     * @param mixed $key
     * @return int
     */
    public function getInt($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getInt($this->_source[$key]);
    }

    /**
     * Returns realpath(value).
     *
     * @param mixed $key
     * @return mixed
     */
    public function getPath($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::getPath($this->_source[$key]);
    }

    /**
     * Returns value.
     *
     * @param string $key
     * @return mixed
     */
    public function getRaw($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return $this->_source[$key];
    }

    /**
     * Returns value if every character is alphabetic or a digit,
     * FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testAlnum($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isAlnum($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if every character is alphabetic, FALSE
     * otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testAlpha($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isAlpha($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is greater than or equal to $min and less
     * than or equal to $max, FALSE otherwise. If $inc is set to
     * FALSE, then the value must be strictly greater than $min and
     * strictly less than $max.
     *
     * @param mixed $key
     * @param mixed $min
     * @param mixed $max
     * @param boolean $inclusive
     * @return mixed
     */
    public function testBetween($key, $min, $max, $inc = TRUE)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isBetween($this->_source[$key], $min, $max, $inc)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid credit card number format. The
     * optional second argument allows developers to indicate the
     * type.
     *
     * @param mixed $key
     * @param mixed $type
     * @return mixed
     */
    public function testCcnum($key, $type = NULL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isCcnum($this->_source[$key], $type)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns $value if it is a valid date, FALSE otherwise. The
     * date is required to be in ISO 8601 format.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testDate($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isDate($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if every character is a digit, FALSE otherwise.
     * This is just like isInt(), except there is no upper limit.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testDigits($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isDigits($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid email format, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testEmail($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if(empty($this->_source[$key]))
        {
            return '';
        }
        if ($this->check_email_address($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid float value, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testFloat($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isFloat($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is greater than $min, FALSE otherwise.
     *
     * @param mixed $key
     * @param mixed $min
     * @return mixed
     */
    public function testGreaterThan($key, $min = NULL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isGreaterThan($this->_source[$key], $min)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid hexadecimal format, FALSE
     * otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testHex($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isHex($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid hostname, FALSE otherwise.
     * Depending upon the value of $allow, Internet domain names, IP
     * addresses, and/or local network names are considered valid.
     * The default is HOST_ALLOW_ALL, which considers all of the
     * above to be valid.
     *
     * @param mixed $key
     * @param integer $allow bitfield for HOST_ALLOW_DNS, HOST_ALLOW_IP, HOST_ALLOW_LOCAL
     * @return mixed
     */
    public function testHostname($key, $allow = Zend_Filter::HOST_ALLOW_ALL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isHostname($this->_source[$key], $allow)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid integer value, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testInt($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isInt($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid IP format, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testIp($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isIp($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is less than $max, FALSE otherwise.
     *
     * @param mixed $key
     * @param mixed $max
     * @return mixed
     */
    public function testLessThan($key, $max = NULL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isLessThan($this->_source[$key], $max)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is one of $allowed, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testOneOf($key, $allowed = NULL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isOneOf($this->_source[$key], $allowed)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid phone number format, FALSE
     * otherwise. The optional second argument indicates the country.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testPhone($key, $country = 'US')
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isPhone($this->_source[$key], $country)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it matches $pattern, FALSE otherwise. Uses
     * preg_match() for the matching.
     *
     * @param mixed $key
     * @param mixed $pattern
     * @return mixed
     */
    public function testRegex($key, $pattern = NULL)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isRegex($this->_source[$key], $pattern)) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    public function testUri($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isUri($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value if it is a valid US ZIP, FALSE otherwise.
     *
     * @param mixed $key
     * @return mixed
     */
    public function testZip($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        if (Zend_Filter::isZip($this->_source[$key])) {
            return $this->_source[$key];
        }

        return FALSE;
    }

    /**
     * Returns value with all tags removed.
     *
     * @param mixed $key
     * @return mixed
     */
    public function noTags($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::noTags($this->_source[$key]);
    }

    /**
     * Returns basename(value).
     *
     * @param mixed $key
     * @return mixed
     */
    public function noPath($key)
    {
        if (!$this->keyExists($key)) {
            return false;
        }
        return Zend_Filter::noPath($this->_source[$key]);
    }

    /**
     * Checks if a key exists
     *
     * @param mixed $key
     * @return bool
     */
    public function keyExists($key)
    {
       return array_key_exists($key, $this->_source);
    }
    
    public function setSource( $name, $value )
    {
        $this->_source[$name] = $value;
    }
    
    private function check_email_address($email) 
    {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
        {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) 
        {
            if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
            {
                return false;
            }
        }  
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
        { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2)
            {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) 
            {
                if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
                {
                    return false;
                }
            }
        }
        return true;
}
}

?>
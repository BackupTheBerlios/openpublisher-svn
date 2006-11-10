<?php
// ---------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/*
 * The Session Class
 *
 */

class JapaCommonSession
{
    /**
     * Constructor
     *
     */    
    public function __construct( $session_name = FALSE )
    {
        ini_set('session.use_only_cookies', '1');     
        ini_set('session.use_trans_sid',    '0');
        
        if($session_name != FALSE)
        {
            session_name( $session_name );
        }
        
        session_start();
    }

    /**
     * get session id
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Tests if a session var is present
     *
     * @param string $name The nam of the session var
     * @return bool
     */
    public function exists($name)
    {
        if (empty($name))
            return false;

        return (array_key_exists($name, $_SESSION));
    }

    /**
     * Return a copy of a session var
     *
     * @param string $name The nam of the session var
     * @return mixed The session var if any or NULL
     * @see opSession::getRef()
     */
    public function get($name)
    {
        if ($this->exists($name))
            return $_SESSION[$name];
        else
            return NULL;
    }

    /**
     * Set a session var
     *
     * @param string $name The nam of the session var
     * @param mixed $value The var
     * @return bool
     */
    public function set($name, $value)
    {
        if (empty($name))
            return false;

        $_SESSION[$name] = $value;
        
        session_register($name);

        return true;
    }

    /**
     * Removes a var from the session. Take also care on the global
     * scope.
     *
     * @param string $name The name of the var
     */
    public function del($name)
    {
        if (empty($name))
            return false;
        
        $_SESSION[$name] = NULL;
        
        session_unregister($name);
        
        return true;
    }

    /**
     * Returns the reference to a session var
     *
     * @param string $name The name of the session var
     * @return reference to the session var
     * @see opSession::get()
     */
    public function &getReference($name)
    {
        if ($this->exists($name))
            return $_SESSION[$name];
    }
    
    /**
     * Delete all session vars
     *
     */
    public function del_all()
    {
        session_unset();
    } 
    
    /**
     * Destroy the session
     *
     */
    public function destroy()
    {
        $CookieInfo = session_get_cookie_params();
        
        if ( (empty($CookieInfo['domain'])) && (empty($CookieInfo['secure'])) ) 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path']);
        } 
        elseif (empty($CookieInfo['secure'])) 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path'], $CookieInfo['domain']);
        } 
        else 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
        }    
        session_unset();
        session_destroy();
    } 
} 
?>
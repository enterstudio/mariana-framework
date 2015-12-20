<?php namespace Mariana\Framework;

class Session{

    // Singleton Class
    private static $_instance;

    public static function getInstance($config = array())
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    //  DB
    private $_db;
    private $_https;
    private $_user_agent;
    private $_ip_address;
    private $_expiry =  7200;
    private $_session_cookie_ttl    =  0; //[$_session_cookie_ttl Session Cookie Lifetime , default (0:Clear the session cookies on browser close) ]
    private $_refresh_interval      =  600; //[$_refresh_interval Refresh Interval toi regenerate Session Id, default 10 minutes]
    private $_table_name            = "sessions";
    private $_session_id;   //$_session_id Holder for session_id
    const SECURE_SESSION            = 'salt';

    public function __construct(array $config)
    {
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        $this->_setConfig($config);
        session_start();
    }


    private function _setConfig($config)
    {
        $this->_db                  = $config['dbconnector'];
        $this->_expiry              = (isset($config['expiry']))? $config['expiry'] : $this->_expiry ;
        $this->_session_cookie_ttl  = (isset($config['session_cookie_ttl']))? $config['session_cookie_ttl'] : $this->_session_cookie_ttl ;
        $this->_https               = (isset($_SERVER['HTTPS'])) ? TRUE: FALSE;
        $this->_refresh_interval    = (isset($config['refresh_interval'])) ? $config['refresh_interval']: $this->_refresh_interval;
        $this->_user_agent          = isset($config['user_agent']) ? $config['user_agent'] : $_SERVER['HTTP_USER_AGENT'];
        $this->_ip_address          = $this->_getRealIpAddr();

        ini_set('session.cookie_lifetime', $this->_session_cookie_ttl);
        ini_set('session.gc_maxlifetime',  $this->_expiry);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.entropy_file', '/dev/urandom');
        ini_set('session.hash_function', 'whirlpool');
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', $this->_https);
        ini_set('session.entropy_length' ,512);
        ini_set('session.use_trans_sid', false);

        $stmt_create = "CREATE TABLE IF NOT EXISTS {$this->_table_name} (
                        `session_id` varchar(255) NOT NULL,
                        `data` text,
                        `user_agent` varchar(255) NOT NULL,
                        `ip_address` varbinary(16) NOT NULL,
                        `last_updated` int(11) NOT NULL,
                        `fingerprint` varchar(255) NOT NULL,
                        PRIMARY KEY (`session_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->_db->exec($stmt_create);
    }

    private function _getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            /*check ip from share internet*/
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            /*to check ip is pass from proxy*/
            $ip     =   $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


    public function open($path, $name)
    {
        return true;
    }

    public function close()
    {
        /*calling explicitly method gc(),that will clear all expired sessions*/
        $this->gc();
        return true;
    }

    private function _refresh()
    {
        session_regenerate_id(true);
        $this->_session_id = session_id();
    }

    private function _needRenewal($id)
    {

        $stmt    = $this->_db->prepare("SELECT last_updated FROM {$this->_table_name} WHERE session_id = ?");
        $stmt->execute(array($id));
        $record  = current($stmt->fetchAll());

        if ($record !== FALSE && count($record) > 0)
        {
            /*Checks if the session ID has exceeded it's permitted lifespan.*/
            if((time() - $this->_refresh_interval) > $record['last_updated'])
            {
                /*Regenerates a new session ID*/
                $this->_refresh();
                $sql = "UPDATE {$this->_table_name} SET session_id =:session_id, last_updated =:last_updated WHERE session_id = '$id'";
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(':last_updated', $id , PDO::PARAM_INT);
                $stmt->bindParam(':session_id', $this->_session_id , PDO::PARAM_STR); //this is what will be returned by Refresh
                $stmt->execute();
                return true;
            }
        }

        return false;
    }

    private function _isExpired($record)
    {
        $ses_life = time() - $this->_expiry;
        $stmt     = $this->_db->prepare("SELECT session_id FROM {$this->_table_name} WHERE last_updated < ? AND session_id = ?");
        $stmt->execute(array($ses_life, $record['session_id']));
        $record   = current($stmt->fetchAll());

        if($record)
            return true;
        else
            return false;
    }

    public function read($id)
    {
        try
        {
            $stmt = $this->_db->prepare("SELECT session_id, fingerprint, data, user_agent, INET6_NTOA(ip_address), last_updated  FROM {$this->_table_name} WHERE  session_id = ?");
            $stmt->execute(array($id));
            $record = current($stmt->fetchAll());
            if(empty($record['session_id']))
            {
                $this->_refresh();
                return '';
            }
            else
            {
                if($this->_isSuspicious($record['fingerprint']) || $this->_isExpired($record))
                {
                    $this->destroy($id);
                    throw new Exception('Possible Session Hijack attempt/Session expired/Some mismatch.');
                }
                else
                {
                    /*Need a renewal ?*/
                    if($this->_needRenewal($id))
                    {
                        /*recursive call*/
                        $this->read($this->_session_id);
                    }

                    return $record['data'];
                }

            }

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            $this->_refresh();
            return '';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            $this->_refresh();
            return '';
        }
    }

    private function _getFingerPrint()
    {
        return md5($this->_user_agent.self::SECURE_SESSION . $this->_ip_address);
    }

    private function _isSuspicious($fp)
    {
        return ($fp != $this->_getFingerPrint()) ? True : False;
    }

    public function write($id, $data)
    {

        try
        {

            $sql = "INSERT INTO {$this->_table_name} (session_id, user_agent, ip_address, last_updated, data, fingerprint)
                        VALUES (:session_id, :user_agent, INET6_ATON(:ip_address), :last_updated, :data,:fingerprint)
                        ON DUPLICATE KEY UPDATE data =VALUES(data), last_updated=VALUES(last_updated)";

            $time   = time();
            $fp     = $this->_getFingerPrint();

            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(':session_id', $id , PDO::PARAM_STR);
            $stmt->bindParam(':user_agent', $this->_user_agent, PDO::PARAM_STR);
            $stmt->bindParam(':ip_address' , $this->_ip_address , PDO::PARAM_STR);
            $stmt->bindParam(':last_updated', $time , PDO::PARAM_INT);
            $stmt->bindParam(':data', $data , PDO::PARAM_STR);
            $stmt->bindParam(':fingerprint', $fp, PDO::PARAM_STR);
            $stmt->execute();
            return true;

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy($id)
    {
        /**/
        $stmt           = $this->_db->prepare("DELETE FROM {$this->_table_name} WHERE  session_id =  ?");
        $session_res    = $stmt->execute(array($id));

        if (!$session_res)
            return false;
        else
            return true;

    }
    public function gc()
    {
        $ses_life       = time() - $this->_expiry;
        $stmt           = $this->_db->prepare("DELETE FROM {$this->_table_name} WHERE  last_updated < ?");
        $session_res    = $stmt->execute(array($ses_life));

        return true;
    }

    public function __destruct()
    {
        register_shutdown_function('session_write_close');
    }

}


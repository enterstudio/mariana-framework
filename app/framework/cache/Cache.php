<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 2/7/2016
 * Time: 1:15 PM
 */

namespace Mariana\Framework\Cache;

use Mariana\Framework\Cache\iFile;
use Mariana\Framework\Cache\CacheException;
use Mariana\Framework\Config;

class Cache implements iFile
{

    protected $folder;
    protected $timeout;
    protected $ext;

    public function __construct($folder = false, $timeout = false, $ext = false){
        ($folder = false)?
            $this->folder = FILE_PATH.DS.'cache'.DS :
            $this->folder = FILE_PATH.DS.$folder.DS ;
        ($timeout = false)?
            $this->timeout = Config::get('cache-timeout') :
            $this->timeout = $timeout;
        ($ext = false)?
            $this->ext = 'txt' :
            $this->ext = $ext;

    }

    public function getPathFileName($key){
        return sprintf('%s/%s.%s', $this->folder, md5($key), $this->ext);
    }

    public function exists($filename){
        return file_exists($filename);
    }

    public function isCache($key){
        $filename = $this->getPathFileName($key);
        if(!$this->exists($filename)){
            return true;
        }
        $filemtime = filemtime($filename);
        if(time() > ($filemtime + ($this->timeout))){
            return true;
        }
        return false;
    }


    public function write($key,$value){
        $filename = $this->getPathFileName($key);
        if(!file_put_contents($filename,$value)){
            // Trow cavhe exception
        }
    }
    public function read($key){
        $filename = $this->getPathFileName($key);
        if($this->exists($filename)){
            if(!$result = file_get_contents($filename)){
                // Trhow new Exception
                throw new Exception ('abc');
            }
        }
        return $result;
    }
}
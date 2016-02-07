<?php
/**
 * User: filipe
 * Date: 2/7/2016
 * Time: 4:06 PM
 */

namespace Mariana\Framework\Upload;


use Mariana\Framework\Config;
use Mariana\Framework\Session\Flash;
use Mariana\Framework\Language\Lang;

class Upload
{

    private $error_count = 0;
    private $error_messages = array();
    private $file_name;
    private $file_size;
    private $file_ext;
    private $file_type;
    private $file_tmp;

    private function check($id){
        /**
         * @param $id
         * @return bool
         * @desc checks if exists $_FILE['name']
         */
        if(isset($_FILES[$id])){
            return $_FILES[$id];
        }
        $this->setErrors(Lang::get(3));
        Flash::setMessages($this->error_messages);
        return false;
    }

    private function setErrors($message){
        /**
         * @param $message
         * @desc sets the errors on flash messages
         */
        $this->error_count++;
        if(!in_array($message,$this->error_messages)) {
            array_push($this->error_messages, $message);
        }
    }

    private function validate($file){
        /**
         * @param $file
         * @return bool
         * @desc validates files before uploading them
         */

        # Property check
        $this->file_name = $file['name'];
        $this->file_tmp = $file['tmp_name'];
        $this->file_size = $file['size'];
        $this->file_type = $file['type'];
        $this->file_ext = explode('.',$this->file_name);
        $this->file_ext = end($this->file_ext);
        $file_error = $file['error'];

        # Error tracking
        ## File error
        if($file_error !== 0){
            $this->setErrors(Lang::get(0).$file_error);
        }

        ## Check if it's allowed file type
        if(!in_array($this->file_type, Config::get('file-upload')['allowed-file-types'])){
            $this->setErrors(Lang::get(1) .$this->file_type);
        }

        ## Check if it's allowed extension
        if(!in_array($this->file_ext, Config::get('file-upload')['allowed-file-extensions'])){
            $this->setErrors(Lang::get(1) .$this->file_ext);
        }

        ## Check if it's smaller than maximum file size
        if($this->file_size >= Config::get('file-upload')['max-file-size']){
            $this->setErrors(Lang::get(2));
        }

        if($this->error_count > 0){
            Flash::setMessages($this->error_messages);
            return false;
        }

        return true;
    }

    public function move($file , $destination = false){
        /**
         * @param $file
         * @param bool|false $destination
         * @return bool
         * @desc moves the file to the correct destination
         */
        if($this->validate($file)){

            # Unique Name;
            $file_name_new = time().'_'.uniqid('', true).'.'.$this->file_ext;

            # Setting the destination
            if($destination == false){
                $destination = FILE_PATH.DS.'uploads';
            }
            $destination = trim(trim($destination,DS),'/').DS.$file_name_new;


            # Move the uploaded file
            if(move_uploaded_file($this->file_tmp, $destination)){
                return $destination;
            }else{
                Flash::setMessages(array(Lang::get(4)));
                return false;
            }
        }
    }

    public function single($name , $destination = false){
        /**
         * @param $name
         * @param bool|false $destination
         * @desc single file upload process
         */
        $file = $this->check($name);
        if($file){
            $this->move($file,$destination);
        }
    }

    public function multiple($name, $destination = false){
        $file= $this->check($name);
        $return_array = array();
        if($file){
            # Reorganize the files array
            $array_reorganized = array();
            $size = sizeof($file['name']);
            $i = 0;

            while($i < $size){
                array_push($array_reorganized,array(
                    'name'  =>  $file['name'][$i],
                    'type'  =>  $file['type'][$i],
                    'tmp_name'  =>  $file['tmp_name'][$i],
                    'error' =>  $file['error'][$i],
                    'size'  =>  $file['size'][$i]
                ));
                $i++;
            }

            foreach($array_reorganized as $f){
                array_push($return_array,$this->move($f,$destination));
            }
            return $return_array;
        }
    }

}

?>
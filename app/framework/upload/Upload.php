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
    private $file_tmp_name;

    private function check($id){
        if(isset($_FILES[$id])){
            return $_FILES[$id];
        }
        $this->setErrors(Lang::get(3));
        Flash::setMessages($this->error_messages);
        return false;
    }

    private function setErrors($message){
        $this->error_count++;
        array_push($this->error_messages,$message);
    }

    private function validate($file){

        # SINGLE FILE VALIDATION

        # Propertys
        if(!is_array($file['name'])){
            $this->file_name = $file['name'];
            $this->file_tmp = $file['tmp_name'];
            $this->file_size = $file['size'];
            $this->file_ext = explode('.',$this->file_name);
            $this->file_ext = end($this->file_ext);
            $file_error = $file['error'];

            # Error tracking
            ## File error
            if($file_error !== 0){
                $this->setErrors(Lang::get(0).$file_error);
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

        }

        return true;
    }

    public function single($name , $destination = false){
        $file = $this->check($name);
        if($file){

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
                    return true;
                }else{
                    Flash::setMessages(array(Lang::get(4)));
                    return false;
                }
            }
        }
    }


    public function multiple($destination = false){

    }


}

?>
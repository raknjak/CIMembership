<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_member_profile_picture
{

    private $_ci;

    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_ci->load->library('form_validation');

        log_message('debug', 'Upload profile picture class Initialized');
    }

    /**
     *
     * upload_profile_picture
     *
     * @param array $username
     * @return array
     *
     */

    public function upload($username) {

        require APPPATH . 'vendor/Gargron-FileUpload/autoload.php';

        // Simple validation
        $validator = new FileUpload\Validator\Simple(Settings_model::$db_config['picture_max_upload_size'] * 1000, ['image/png', 'image/jpg', 'image/jpeg']);

        // Simple path resolver, where uploads will be put
        $pathresolver = new FileUpload\PathResolver\Simple('uploads');

        // The machine's filesystem
        $filesystem = new FileUpload\FileSystem\Simple();

        // FileUploader itself
        $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);

        // Adding it all together. Note that you can use multiple validators or none at all
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);

        // Doing the deed
        $response = $fileupload->processAll();
        $files = $response[0];
        $headers = $response[1];

        foreach($files as $file){
            //Remeber to check if the upload was completed
            if ($file->completed) {
                $real_path =  $file->getRealPath();
                // Move file to correct member image directory
                $path_parts = pathinfo($real_path);
                $ext = $path_parts['extension']; // get the extension of the file
                $new_name = md5(uniqid(mt_rand(), true)) .".". strtolower($ext); // set new name with dynamic extension

                $glob = glob(FCPATH .'assets/img/members/'. $username .'/*.{jpg,jpeg,png}', GLOB_BRACE);
                $this->_delete_profile_pictures($glob);

                $filesystem->moveUploadedFile($real_path, FCPATH .'assets/img/members/'. $username .'/'. $new_name);
            }
        }


        // Outputting it, for example like this
        foreach($headers as $header => $value) {
            header($header . ': ' . $value);
        }

        return array('files' => $files, 'new_name' => $new_name, "csrfHash" => $this->_ci->security->get_csrf_hash());
    }

    /**
     *
     * delete_profile_picture
     *
     * @param array $username
     * @return bool
     *
     */

    public function delete($username) {
        $glob = glob(FCPATH .'assets/img/members/'. $username .'/*.{jpg,jpeg,png}', GLOB_BRACE);

        if ($glob) {
            $this->_delete_profile_pictures($glob);
            return true;
        }

        return false;
    }

    /**
     *
     * _delete_profile_pictures
     *
     * @param array $glob
     *
     */

    private function _delete_profile_pictures($glob) {
        foreach ($glob as $img) {
            unlink($img);
        }
    }

}
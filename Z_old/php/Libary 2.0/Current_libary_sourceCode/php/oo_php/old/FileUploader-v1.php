<?php

// confimed support png, jpeg
// may support but not tested svg, webp, bmp, gif,
class FileUploader {
    private $postName;

    private $tmp_name;
    private $file_name;
    private $file_size;

    private $file_type; // is header given by browser
    private $file_mime; // is a check got from a method
    private $file_extention; // is the extention formatted as file_mime and file_type

    private $raw_extention; // is the raw extention

    public function __construct($postName) {
        // set limits of file uploads
        // ini_set("memory_limit", "2000M"); // max memory
        ini_set("post_max_size", "30M"); // max total post size
        ini_set("upload_max_filesize", "2M"); // max size per file
        $this->postName = $postName;
    }

    public function UploadImage($folder, $name) {
        $postName = $this->postName;
        $url = $folder . "/" . $name;

        if (count($_FILES) && !empty($_FILES["$postName"]["name"]) ) {
            $this->SetVariables();

            if ($this->ValidateFile() ) {
                switch ($this->file_mime) {
                    case "image/jpg":
                    case "image/jpeg":
                    case "image/png":
                    case "image/svg":
                    case "image/webp":
                    case "image/gif":
                    case "image/bmp":
                        $this->MoveFile($url);
                        break;
                }
            }
        }
    }

    private function SetVariables() {
        // set program variables
        $this->tmp_name = $_FILES['fileupload']['tmp_name'];
        $this->file_name = $_FILES['fileupload']['name'];
        $this->file_type = $_FILES['fileupload']['type'];
        $this->file_size = $_FILES['fileupload']['size'];

        $this->file_mime = $this->GetFile_mime($this->tmp_name);

        $extentionArray = $this->GetExtention($this->file_name);
        $this->raw_extention = $extentionArray[0];
        $this->file_extention = $extentionArray[1];
    }

    private function GetFile_mime($tmp_name) {
        // Get Mine type of the file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        return $file_mime;
    }

    private function GetExtention($file_name) {
        // Get file extention and prevent double extention
        $fileNameArray = explode(".", $file_name);
        if (count($fileNameArray) > 2) {
            echo "<pre>";
            throw new Exception("FileNames With more then 1 . are not allowed", 1);
            echo "</pre>";
        } else {
            $raw_extention = $fileNameArray[1];
        }

        // convert extention to match header type and mimetype format

        switch ($raw_extention) {
            case "jpg":
            case "jpeg":
                $file_extention = "image/jpeg";
                break;
            case "png":
                $file_extention = "image/png";
                break;
            case "svg":
                $file_extention = "image/svg";
                break;
            case "webp":
                $file_extention = "image/webp";
                break;
            case "gif":
                $file_extention = "image/gif";
                break;
            case "bmp":
                $file_extention = "image/bmp";
                break;
            default:
                return 0;
                break;
        }

        return [$raw_extention, $file_extention];
    }

    private function ValidateFile() {

        // disallow upload if reports of the browser header, extention or mime type do not match.
        if ($this->file_type !== $this->file_mime) {
            echo "<pre>";

            throw new Exception("File Validation Fails
            \n \$file_type !== \$file_mime \n", 1);

            echo "</pre>";

        } else if ($this->file_mime !== $this->file_extention) {
            echo "<pre>";

            throw new Exception("File Validation Fails
            \n \$file_mime !== \$file_extention
            \n \$file_mime => $this->file_mime
            \n \$file_extention => $this->file_extention", 1);

            echo "</pre>";
        } else {
            return 1;
        }
    }

    private function MoveFile($url) {
        if(!move_uploaded_file($this->tmp_name, "$url.$this->raw_extention")){
            echo "<pre>";
            throw new Exception("Error uploading file", 1);
            echo "</pre>";
        }
    }
}
?>

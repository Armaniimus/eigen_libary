<?php
// confimed support png, jpeg
// may support but not tested webp, bmp, gif
// will not support svg due to the nature of it having executeable code
class FileUploader {
    private $testData;

    private $filesPostName;

    private $tmp_name;
    private $file_name;
    private $file_size;

    private $file_type;         // is header given by browser
    private $file_mime;         // is a check got from a method
    private $file_extention;    // is the extention formatted as file_mime and file_type

    private $raw_extention;     // is the raw extention
    private $NULLFileName;      // is the given filename without extention
    private $lastUploadedFile;  // is the full url of the uploaded file

    public function __construct($filesPostName) {
        $this->testData = 0;
        $lastUploadedFile = NULL;

        // set limits of file uploads
        ini_set("post_max_size", "30M"); // max total post size
        ini_set("upload_max_filesize", "2M"); // max size per file

        // set post variable.
        $this->filesPostName = $filesPostName;

        // set image extracted variables
        if (count($_FILES) && !empty($_FILES["$filesPostName"]["name"]) ) {
            $this->SetVariables();
        }
    }

    // uploads image to the folder given in the variable
    // and gives the file the name thats given in the variable or keep the upload name of the file
    public function UploadImage($folder, $fileName = NULL) {
        if ($fileName === NULL) {
            $fileName = $this->NULLFileName;
        }

        $filesPostName = $this->filesPostName;
        $localUrl = $folder . "/" . $fileName;

        // Check if there is something inside of $_files and if needed variable is not empty
        if (count($_FILES) && !empty($_FILES["$filesPostName"]["name"]) ) {

            if ($this->ValidateFile() ) {
                switch ($this->file_mime) {
                    case "image/jpg":
                    case "image/jpeg":
                    case "image/png":
                    case "image/webp":
                    case "image/gif":
                    case "image/bmp":
                        $this->MoveFile($localUrl);
                        break;
                }
            }

            if ($this->testData === 1) {
                echo $this->file_type . "<br>";
                echo $this->file_mime . "<br>";
                echo $this->file_extention . "<br>";
            }
        }
        return $this->GetLastUploadedFile();
    }

    public function GetRawExtention() {
        return $this->raw_extention;
    }

    public function GetLastUploadedFile() {
        return $this->lastUploadedFile; //leads to the url of the file
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
        $test1 = explode("/", $file_name);
        $test2 = explode("\\", $file_name);

        if (count($test1) + count($test2) > 2) {
            $this->ThrowError("user has added a forbidden character to the filename");
        }

        // Get file extention and prevent double extention
        $fileNameArray = explode(".", $file_name);
        if (count($fileNameArray) > 2) {
            $this->ThrowError("FileNames With more then 1 . are not allowed");

        } else {
            $raw_extention = $fileNameArray[1];
            $this->NULLFileName = $fileNameArray[0];
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
        $filesPostName = $this->filesPostName;

        // dissallow upload if upload file doesn't match
        if (!is_uploaded_file($_FILES["$filesPostName"]['tmp_name']) ) {
            $this->ThrowError("user tried to upload a file but file name didn't match the actual upload");
            return 0;
        }

        // disallow upload if reports of the browser header, extention or mime type do not match.
        else if ($this->file_type !== $this->file_mime) {
            $this->ThrowError("File Validation Fails\n \$file_type !== \$file_mime \n");
            return 0;

        //  disallow upload if mimetype does not match the file extention
        } else if ($this->file_mime !== $this->file_extention) {

            $message = "File Validation Fails
            \n \$file_mime !== \$file_extention
            \n \$file_mime => $this->file_mime
            \n \$file_extention => $this->file_extention";

            $this->ThrowError("$message");
            return 0;

        // allow upload if there are no <?php tags or <script tags in the file>
        } else if ($this->InDeptValidation() ) {
            return 1;
        }
    }

    private function InDeptValidation() {
        $content            =   file_get_contents($this->tmp_name);
        $screenedContent    =   file_get_contents($this->tmp_name);

        // Scan for Html and script tags
        $screenedContent    =   str_replace("script","","$screenedContent");
        $screenedContent    =   str_replace("<?php","","$screenedContent");

        // if there are no php or script tags found return true
        if ($screenedContent === $content) {
            unset($content);
            unset($screenedContent);
            return 1;
        }
    }

    private function MoveFile($url) {
        $this->lastUploadedFile = "$url.$this->raw_extention";

        if(!move_uploaded_file($this->tmp_name, $this->lastUploadedFile) ) {
            $this->ThrowError("Error uploading file");
        }
    }

    private function ThrowError($message) {
        echo "<pre>";
        throw new Exception("$message", 1);
        echo "</pre>";
    }
}
?>

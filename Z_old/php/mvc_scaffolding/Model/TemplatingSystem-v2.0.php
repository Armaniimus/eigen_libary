 <?php

/**
 *
 */
class TemplatingSystem {
    public $tplContent;

    /**
     * this method sets the fileUrl to be used in this class
     * and does a few checks to make sure its a valid tpl file
     * @param string this needs te be a valid local url
     */
    public function __construct($tplUrl = false) {
        if ($tplUrl) {
            $this->loadTemplate($tplUrl);
        }
    }

    public function loadTemplate($tplUrl) {
        // $test if template is not empty or null
        if ($tplUrl == "" && $tplUrl == false) {
            $this->throwError("No string is given"); // "if No string is given";

        // test if the extension is tpl
        } else if (!preg_match("#(.+?).tpl#si", $tplUrl)) {
            $this->throwError("File Extention is wrong");// "if Wrong extention";

        // test if file exists
        } else if (!file_exists($tplUrl)) {
            $this->throwError("File doesn't Exist"); // "if file doesnt exists";
        }

        else {
            $this->tplContent = file_get_contents($tplUrl);
        }
    }

    /**
     * this method is used to replace a specified piece of code in the tpl file with the provided string
     * example if a piece of code in the tpl file looks like this {hi}
     * you can replace it to Hello with setTemplateData("hi", "hello");
     * @param string $pattern     the piece of code needed to be replaced
     * @param string $replacement the replacement
     */
    public function setTemplateData($pattern, $replacement) {
        if ($this->getParsedTemplate() == false) {
            $this->getParsedTemplate(); // do it
        }
        $this->tplContent = preg_replace("#\{" . $pattern . "\}#si", $replacement, $this->tplContent); //{blabla changed to ..}
    }

    /**
     * this method is used to handle the throwExeptions in this class
     * @return string the error to be thrown
     */
    private function throwError($errorMessage) {
        echo "<pre>";
        throw new Exception("$errorMessage", 1);
        echo "</pre>";
    }

    /**
     * this method is used to return the tplData after all conversions
     * @return string the string
     */
    public function getParsedTemplate() {
        return $this->tplContent;
    }

    // $template = new TemplatingSystem("view/default.tpl");
    // $template-> setTemplateData("hallo", "title of page",)
}

// $template = new TemplatateSystem("view/templates/template.tpl");

?>

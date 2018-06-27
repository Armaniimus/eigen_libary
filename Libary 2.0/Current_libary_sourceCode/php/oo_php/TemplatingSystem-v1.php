<?php

/**
 *
 */
class TemplatingSystem {
    public $tplContent;

    public function __construct($tplUrl = false) {

        // $test if template is not empty or null
        if ($tplUrl == "" && $tplUrl == false) {
            $this->ThrowError("No string is given"); // "if No string is given";

        // test if the extension is tpl
    } else if (!preg_match("#(.+?).tpl#si", $tplUrl)) {
            $this->ThrowError("File Extention is wrong");// "if Wrong extention";

        // test if file exists
    } else if (!file_exists($tplUrl)) {
            $this->ThrowError("File doesn't Exist"); // "if file doesnt exists";
        }

        else {
            $this->tplContent = file_get_contents($tplUrl);
        }
    }

    // private function TestTemplate($template) {
    //
    // }

    public function setTemplateData($pattern, $replacement) {
        if ($this->ReadTemplateData() == false) {
            $this->ReadTemplateData(); // do it
        }
        $this->tplContent = preg_replace("#\{" . $pattern . "\}#si", $replacement, $this->tplContent); //{blabla changed to ..}
    }

    private function ReadTemplateData() {
        return $this->tplContent;
    }

    private function ThrowError($errorMessage) {
        echo "<pre>";
        throw new Exception("$errorMessage", 1);
        echo "</pre>";
    }

    public function GetParsedTemplate() {
        return $this->tplContent;
    }

    // $template = new TemplatingSystem("view/default.tpl");
    // $template-> setTemplateData("hallo", "title of page",)
}

// $template = new TemplatateSystem("view/templates/template.tpl");

?>

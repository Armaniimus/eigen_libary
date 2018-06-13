<?php

class ContentLogic {

    private $PhpUtilities;
    private $DataHandler;
    private $DataValidator;

    public $columnNames;
    public $dataTypes;
    public $dataNullArray;

    public function __construct($dbName, $username, $pass, $serverAdress, $dbType) {
        $this->PhpUtilities     =   new PhpUtilities      ();
        $this->DataHandler      =   new DataHandler       ($dbName, $username, $pass, $serverAdress, $dbType);

        $columnNames            =   $this->DataHandler->GetColumnNames("vr_bril");
        $dataTypes              =   $this->DataHandler->GetTableTypes("vr_bril");
        $dataNullArray          =   $this->DataHandler->GetTableNullValues("vr_bril");

        $this->DataValidator    =   new DataValidator     ($columnNames, $dataTypes, $dataNullArray);
    }

    public function __destruct() {
        $this->DataHandler = NULL;
    }

    public function GetSpecificData($id) {
        if ( $this->DataValidator->ValidatePHP_ID($id) ) {
            $sql = "SELECT * FROM vr_bril WHERE id = ?";
            $paramArray = [$id];

            $returnArray = $this->DataHandler->ReadSingleData($sql, $paramArray);
            return $returnArray;

        } else {
            return [];
        }
    }

    public function GetOverViewData() {
        $sql = "SELECT id, naam, prijs, afbeelding, beschrijving FROM vr_bril";
        $returnArray = $this->DataHandler->ReadData($sql);

        return $returnArray;
    }

    public function GetHomeData() {
        $sql = "SELECT id, naam, prijs, afbeelding, beschrijving FROM vr_bril ORDER BY prijs ASC LIMIT 0,6";
        $returnArray = $this->DataHandler->ReadData($sql);

        return $returnArray;
    }
}
?>

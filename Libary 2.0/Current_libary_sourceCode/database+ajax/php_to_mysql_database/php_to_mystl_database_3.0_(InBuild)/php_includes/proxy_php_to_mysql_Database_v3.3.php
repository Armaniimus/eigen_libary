<?php
if (isset($_POST['dbcrud']) ) {
    if ($_POST['dbcrud'] == "create") {
        create($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "read") {
        $dataArray = read($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "update") {
        update($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "delete") {
        deleteColumn($tableNames[0]);
    }
}

function create($tableName, $columnNames) {
    $DB_Crud = new DB_Validation;

    $noId = $DB_Crud->SelectWithCodeFromArray($columnNames, "011");
    $extractedPost = $DB_Crud->ExtractPost($noId);

    if ($DB_Crud->ValidateNotEmpty($noId, $extractedPost)) {
        $extractedPost = $DB_Crud->sanitizeSpecialChars($extractedPost);

        $data = $DB_Crud->CreateDBRecord($tableName, $noId, $extractedPost);
        echo $data;
    } else {
        echo "Sorry not all fields are filled";
    }
}

function read($tableName, $columnNames) {
    $DB_Crud = new DB_Specify_Functions;
    $where = $DB_Crud->ExtractPostIDOnly();
    echo $where;

    $dataArray = $DB_Crud->ReadDBInto2DArray($tableName, $columnNames, $where);
    $dataArray = ArrayToHTMLTable5($dataArray);

    return "$dataArray";
}

function update($tableName, $columnNames) {
    $DB_Crud = new DB_Specify_Functions;
    $where = $DB_Crud->ExtractPostIDOnly();
    echo $where;

    for ($i=1; $i < count($columnNames); $i++) {
        $set = $DB_Crud->updateSet($columnNames[$i], $_POST[$columnNames[$i] ]);
        $DB_Crud->UpdateDBRecord($tableName, $set, $where);
    }
}

function deleteColumn($tableName) {
    $DB_Crud = new DB_Main;
    $where = $DB_Crud->ExtractPostIDOnly();

    echo $DB_Crud->DeleteDBRecord($tableName, $where);
}

if (!isset($dataArray) ) {
    $DB_Crud = new DB_Main;
    $where = "";

    $dataArray = $DB_Crud->ReadDBInto2DArray($tableNames[0], $columnNames[0], $where);
    $dataArray = ArrayToHTMLTable5($dataArray);
}

echo "$dataArray";

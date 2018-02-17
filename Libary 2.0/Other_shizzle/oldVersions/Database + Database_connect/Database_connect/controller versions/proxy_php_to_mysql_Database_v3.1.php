<?php
if (isset($_POST['dbcrud']) ) {
    if ($_POST['dbcrud'] == "create") {
        create($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "read") {
        $dataArray = read($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "update") {
        echo "update";
        update($tableNames[0], $columnNames[0]);
    }

    if ($_POST['dbcrud'] == "delete") {
        deleteColumn($tableNames[0]);
    }
}

function create($tableName, $columnNames) {
    $DB_Crud = new DB_Main;

    $noId = selectWithCodeFromArray($columnNames, "011");
    $data = $DB_Crud->insertIntoDatabase($tableName, $noId);

    echo $data;
}

function read($tableName, $columnNames) {
    $id = $_POST["id"];
    $where = "where id = " . $id;
    $DB_Crud = new DB_Main;

    $dataArray = $DB_Crud->generate2dArrayFromDB($tableName, $columnNames, $where);
    $dataArray = ArrayToHTMLTable5($dataArray);

    return "$dataArray";
}

function update($tableName, $columnNames) {
    $id = $_POST["id"];
    $where = "id = " . $id;
    echo $where;
    echo createWhere($columnNames);
    $DB_Crud = new DB_Main;

    for ($i=1; $i < count($columnNames); $i++) {
        $set = updateSet($columnNames[$i], $_POST[$columnNames[$i] ]);
        $DB_Crud->updateDatabase($tableName, $set, $where);
    }

    $set = updateSet($columnNames[2], $_POST['leeftijd']);
    $DB_Crud->updateDatabase($tableName, $set, $where);
}

function deleteColumn($tableName) {
    $id = $_POST["id"];
    $where = "id =" . $id;
    $DB_Crud = new DB_Main;

    echo $DB_Crud->deleteRecordInDatabase($tableName, $where);
}

if (!isset($dataArray) ) {
    $where = "";
    $DB_Crud = new DB_Main;

    $dataArray = $DB_Crud->generate2dArrayFromDB($tableNames[0], $columnNames[0], $where);
    $dataArray = ArrayToHTMLTable5($dataArray);
}

echo "$dataArray";

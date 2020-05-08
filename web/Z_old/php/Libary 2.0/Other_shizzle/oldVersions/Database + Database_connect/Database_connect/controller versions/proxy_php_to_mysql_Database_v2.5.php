<?php
    // var_dump($_POST);

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
    $noId = selectWithCodeFromArray($columnNames, "011");
    $data = insertIntoDatabase($tableName, $noId);

    echo $data;
}

function read($tableName, $columnNames) {
    $id = $_POST["id"];
    $where = "where id = " . $id;

    $dataArray = generate2dArrayFromDB($tableName, $columnNames, $where);
    $dataArray = ArrayToHTMLTable5($dataArray);

    return "$dataArray";
}

function update($tableName, $columnNames) {
    $id = $_POST["id"];
    $where = "id = " . $id;
    echo $where;
    echo createWhere($columnNames);

    for ($i=1; $i < count($columnNames); $i++) {
        $set = updateSet($columnNames[$i], $_POST[$columnNames[$i] ]);
        updateDatabase($tableName, $set, $where);
    }

    $set = updateSet($columnNames[2], $_POST['leeftijd']);
    updateDatabase($tableName, $set, $where);
}

function deleteColumn($tableName) {
    $id = $_POST["id"];
    $where = "id =" . $id;

    echo deleteRecordInDatabase($tableName, $where);
}

if (!isset($dataArray) ) {
    $where = "";
    $dataArray = generate2dArrayFromDB($tableNames[0], $columnNames[0], $where);
    $dataArray = ArrayToHTMLTable5($dataArray);
}

echo "$dataArray";

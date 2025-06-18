<?php

function insertData($table, $mysqli, $data)
{
    $columns = [];
    $values = [];
    foreach ($data as $key => $val) {
        $columns[] = "`" . $key . "`";
        $values[] = "'" . $val . "'";
    }
    $column = implode(', ', $columns);
    $value = implode(', ', $values);
    $sql = "INSERT INTO `$table` 
            ($column)
            VALUES 
            ($value)";
    return $mysqli->query($sql);
}

function selectData($table, $mysqli, $column = "*", $where = "", $order = "")
{
    $sql = "SELECT $column FROM `$table` $where $order";
    return $mysqli->query($sql);
}

function deleteData($table, $mysqli, $where)
{
    $sql = "DELETE FROM `$table` WHERE $where";
    return $mysqli->query($sql);
}

function updateData($table, $mysqli, $data, $where)
{
    $array = [];
    $array2 = [];
    foreach ($data as $key => $val) {
        $array[] = "`$key`='$val'";
    }
    foreach ($where as $key => $val) {
        $array2[] = "`$key`='$val'";
    }
    $values = implode(', ', $array);
    $condition = implode(' AND ', $array2);
    $sql = "UPDATE `$table` SET $values WHERE $condition";
    return $mysqli->query($sql);
}

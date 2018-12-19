<?php

define("DB_USER", "./data/users.txt");
define("DB_DATA", "./data/data.txt");

function createAccount($fname, $lname, $email, $password) {
    $fp       = fopen(DB_USER, "a");
    $username = explode("@", $email);
    $password = hash("sha256", $password);
    $data     = "$fname,$lname,$email,$username[0],$password\n";
    fwrite($fp, $data);
    fclose($fp);
    return $username[0];
}

function isDuplicateEmail($email) {
    $data = file(DB_USER);
    foreach ($data as $singleRow) {
        $rowArray     = explode(",", $singleRow);
        $emailAddress = $rowArray[2];
        if ($email == $emailAddress) {
            return true;
        }
    }
    return false;
}

function isValidUser($username, $password) {
    $fp = fopen(DB_USER, "r");
    while ($data = fgetcsv($fp)) {
        $savedUsername = $data[3];
        $savedPassword = $data[4];
        if (($username == $savedUsername) && (hash("sha256", $password) == $savedPassword)) {
            return true;
        }
    }
    return false;
}

function insertData($title, $description) {

    if (is_readable(DB_DATA)) {
        $dataFromFile = file_get_contents(DB_DATA);
        $data         = json_decode($dataFromFile, true);

        if ($data == NULL) {
            $data      = array();
            $inputData = json_encode($data);
            file_put_contents(DB_DATA, $inputData);
        }
        $dataFromFile = file_get_contents(DB_DATA);
        $data         = json_decode($dataFromFile, true);

        if (array_key_exists($_SESSION['user'], $data)) {
            $newData = array(
                'id'          => getItemId(),
                'title'       => $title,
                'description' => $description,
                'completed'   => "no"
            );
            array_push($data[$_SESSION['user']], $newData);

        } else {
            $newData = array(
                "{$_SESSION['user']}" => array(
                    array(
                        'id'          => 1,
                        'title'       => $title,
                        'description' => $description,
                        'completed'   => "no"
                    )
                )
            );
            $data    = array_merge($data, $newData);
        }
    } else {
        echo "Data is not readable. Please change permission to read";
    }

    if (is_writable(DB_DATA)) {
        $jsonData = json_encode($data);
        file_put_contents(DB_DATA, $jsonData, LOCK_EX);
    } else {
        echo "Data is not writable. Please change permission to write";
    }
}

function getItemId() {
    if (is_readable(DB_DATA)) {
        $dataFromFile = file_get_contents(DB_DATA);
        $data         = json_decode($dataFromFile, true);
        $maxValue     = max(array_column($data[$_SESSION['user']], 'id'));
        $id           = $maxValue + 1;
        return $id;
    } else {
        return false;
    }
}

function displayToDoLists() {
    if (is_readable(DB_DATA)) {
        $dataFromFile = file_get_contents(DB_DATA);
        $data         = json_decode($dataFromFile, true);
        if (array_key_exists($_SESSION['user'], $data)) {
            $data      = $data[$_SESSION['user']];
            $toDoLists = array();
            foreach ($data as $datum) {
                if ($datum['completed'] == "no") {
                    array_push($toDoLists, $datum);
                }
            }
            return $toDoLists;
        } else {
            echo "You didn't add any task. Please add an item first.";
        }
    } else {
        echo "File not readable";
    }
    return false;
}

function completedThisTask($id) {
    $dataFormFile    = file_get_contents(DB_DATA);
    $data            = json_decode($dataFormFile, true);
    $sessionUserData = $data["{$_SESSION['user']}"];

    foreach ($sessionUserData as $key => $singleData) {
        if ($singleData['id'] == $id) {
            $sessionUserData[$key]['completed'] = "yes";
            $updatedData["{$_SESSION['user']}"] = $sessionUserData;
        }
    }

    unset($data["{$_SESSION['user']}"]);
    $data = array_merge($data, $updatedData);

    $jsonEncoded = json_encode($data);
    file_put_contents(DB_DATA, $jsonEncoded, LOCK_EX);
}

function displayCompletedList() {
    $dataFromFile = file_get_contents(DB_DATA);
    $data         = json_decode($dataFromFile, true);
    if (array_key_exists($_SESSION['user'], $data)) {
        $data          = $data["{$_SESSION['user']}"];
        $completedList = array();

        foreach ($data as $datum) {
            if ($datum['completed'] == "yes") {
                array_push($completedList, $datum);
            }
        }
        return $completedList;
    }
    return false;
}

function deleteThisTask($id) {
    $dataFormFile    = file_get_contents(DB_DATA);
    $data            = json_decode($dataFormFile, true);
    $sessionUserData = $data["{$_SESSION['user']}"];

    foreach ($sessionUserData as $key => $singleData) {
        if ($singleData['id'] == $id) {
            unset($sessionUserData[$key]);
            $updatedData["{$_SESSION['user']}"] = $sessionUserData;
        }
    }

    unset($data["{$_SESSION['user']}"]);
    $data = array_merge($data, $updatedData);

    $jsonEncoded = json_encode($data);
    file_put_contents(DB_DATA, $jsonEncoded, LOCK_EX);
}


/*
{"hridoyroy":[{"id":1,"title":"Learn Basic Javascript","description":"Complete Mock Udemy basic javascript course with parctise ","completed":"no"},{"id":2,"title":" Learn Javascript","description":"Complete Mock Udemy basic javascript","completed":"no"},{"id":4,"title":"Themeforest","description":"Upload an item into themeforest","completed":"no"},{"id":5,"title":"title","description":"description","completed":"no"},{"id":6,"title":"title","description":"description","completed":"no"},{"id":7,"title":"title","description":"description","completed":"no"},{"id":8,"title":"Upload my profile picture","description":"upload profile picture into facebook","completed":"no"},{"id":9,"title":"Withdraw money ","description":"Withdraw money from ATM","completed":"no"}],"rana":[{"id":1,"title":"title","description":"description","completed":"no"},{"id":3,"title":"title","description":"description","completed":"no"},{"id":4,"title":"Learn Javascript","description":"Complete","completed":"no"}],"rahul":[{"id":1,"title":"Learn Javascript","description":"Complete Mock Udemy basic javascript course with parctise","completed":"no"}],"jhon":[{"id":1,"title":"Upload my profile picture","description":"facebook","completed":"no"}]}
*/
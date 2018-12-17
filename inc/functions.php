<?php

define("DB_USER", "./data/users.txt");

function createAccount($fname, $lname, $email, $password) {
    $fp       = fopen(DB_USER, "a");
    $username = explode("@", $email);
    $password = hash("sha256",$password);
    $data     = "$fname,$lname,$email,$username[0],$password\n";
    fwrite($fp, $data);
    fclose($fp);
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
        if (($username == $savedUsername) && ( hash("sha256", $password) == $savedPassword )) {
            return true;
        }
    }
    return false;
}
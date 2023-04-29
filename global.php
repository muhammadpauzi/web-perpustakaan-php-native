<?php
session_start();

function url()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return rtrim($protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER["REQUEST_URI"] . '?'), '/');
}


function setFlash(string $key, $type, $message)
{
    $_SESSION[$key] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash(string $key)
{
    if (isset($_SESSION[$key])) {
        $flash = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $flash;
    }
    return false;
}

function guardAuth($redirUrl = false)
{
    if (!(isset($_SESSION['auth']) && $_SESSION['auth']['isLoggedIn']))
        return header("location: " . ($redirUrl ? $redirUrl : url() . "/login.php"));

    return false;
}


function auth()
{
    return $_SESSION['auth'] ?? false;
}

function query($sql)
{
    return getConn()->query($sql);
}

function isEmpty($values)
{
    return count(array_filter($values, fn ($value) => empty($value))) > 0;
}

function uploadImage($file)
{
    //Check if the file is well uploaded
    if ($file['error'] > 0) {
        setFlash('message_file', 'danger', 'Gagal dalam proses pengupload-and, coba lagi!');
        return false;
    }

    //We won't use $file['type'] to check the file extension for security purpose

    //Set up valid image extensions
    $extsAllowed = array('jpg', 'jpeg', 'png', 'gif');

    //Extract extention from uploaded file
    //substr return ".jpg"
    //Strrchr return "jpg"

    $extUpload = strtolower(substr(strrchr($file['name'], '.'), 1));

    //Check if the uploaded file extension is allowed

    if (in_array($extUpload, $extsAllowed)) {

        $filenameWithoutExt = rtrim($file['name'], $extUpload);

        //Upload the file on the server
        $name = "img/$filenameWithoutExt" . time() . '.' . $extUpload;
        $result = move_uploaded_file($file['tmp_name'], $name);

        if ($result) {
            return $name;
        }
    } else {
        setFlash('message_file', 'danger', 'File tidak cocok, coba lagi dengan tipe file .jpg, jpeg, png & gif!');
        return false;
    }
}

function generateCode($prefix = "PRF")
{
    return sprintf("%s-%s-%s", $prefix, date('dmyhi'), strtoupper(substr(uniqid(), 0, 4)));
}

function formatDate($datetime, $format)
{
    return $datetime ? date($format, strtotime($datetime)) : null;
}

function formatRupiah($value)
{
    return number_format($value, 2, ',', '.');
}

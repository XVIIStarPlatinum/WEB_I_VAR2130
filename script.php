<?php
function validateX($xVal){
    if(isset($xVal) and is_numeric($xVal)){
        $x_nums = [-2, -1.5, -1, -0.5, 0, 0.5, 1, 1.5, 2];
        return in_array($xVal, $x_nums);
    }
    return false;
}

function validateY($yVal){
    $MIN_Y = -3;
    $MAX_Y = 3;
    $numY = str_replace(',', '.', $yVal);
    if(isset($yVal) && is_numeric($yVal)){
        return $numY >= $MIN_Y && $numY <= $MAX_Y;
    }
    return false;
}
function validateRad($rVal){
    $MIN_R = 2;
    $MAX_R = 5;
    $numR = str_replace(',', '.', $rVal);
    if(isset($rVal) && is_numeric($rVal)){
        return $numR >= $MIN_R && $numR <= $MAX_R;
    }
    return false;
}

function validateForm($xVal, $yVal, $rVal){
    if (validateX($xVal) && validateY($yVal) && validateRad($rVal)){
        return true;
    } else {
        header('HTTP/1.0 412 Precondition Failed');
        return false;
}
function calcTriangle($xVal, $yVal, $rVal){
    return $xVal >= 0 && $yVal <= 0 && $xVal <= $rVal && $yVal >= -$rVal && ($rVal * $rVal)/2 >= abs($yVal) + abs($xVal);
}
function calcRectangle($xVal, $yVal, $rVal){
    return $xVal <= 0 && $yVal >= 0 && $xVal <= $rVal / 2 && $yVal <= $rVal;
}
function calcCircle($xVal, $yVal, $rVal){
    return $xVal <= 0 && $yVal <= 0 && -$xVal <= $rVal && -$yVal <= $rVal && sqrt($xVal * $xVal + $yVal * $yVal) <= $rVal;
}

function calcHit($xVal, $yVal, $rVal){
    return calcTriangle($xVal, $yVal, $rVal) || calcRectangle($xVal, $yVal, $rVal) || calcCircle($xVal, $yVal, $rVal);
}

session_start();

$xVal = $_POST['xval'];
$yVal = $_POST['yval'];
$rVal = $_POST['rval'];

$timezone = $_POST['timezone'];

$isValid = validateForm($xVal, $yVal, $rVal);
$converted_isValid = $isValid ? 'true' : 'false';
$isHit = $isValid && calcHit($xVal, $yVal, $rVal);
$converted_isHit = $isHit ? 'true' : 'false';

$cTime = date('D, d M Y H:i:s', time() - $timezone * 60);
$exTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);
if($isValid){
    $jsonData = '{' .
        "\"validate\":$converted_isValid," .
        "\"xval\":\"$xVal\"," .
        "\"yval\":\"$yVal\"," .
        "\"rval\":\"$rVal\"," .
        "\"cTime\":\"$cTime\"," .
        "\"exTime\":\"$exTime\"," .
        "\"hitRes\":$converted_isHit" .
        '}';
    echo $jsonData;
}

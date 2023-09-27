<?php
function validateX($xVal){
    return isset($xVal);
}

function validateY($yVal){
    $MIN_Y = -3;
    $MAX_Y = 3;
    if(!isset($yVal)) {
        return false;
    }
    $numY = str_replace(',', '.', $yVal);
    return is_numeric($numY) && $numY >= $MIN_Y && $numY <= $MAX_Y;
}
function validateRad($rVal){
    $MIN_R = 2;
    $MAX_R = 5;
    if(!isset($rVal)){
        return false;
    }
    $numR = str_replace(',', '.', $rVal);
    return is_numeric($numR) && $numR >= $MIN_R && $numR <= $MAX_R;
}

function validateForm($xVal, $yVal, $rVal){
    return validateX($xVal) && validateY($yVal) && validateRad($rVal);
}
function calcTriangle($xVal, $yVal, $rVal){
    return $xVal >= 0 && $yVal <= 0 && $xVal >= $rVal && $yVal <= -$rVal && ($rVal * $rVal)/2 >= abs($yVal) + abs($xVal);
}
function calcRectangle($xVal, $yVal, $rVal){
    return $xVal <= 0 && $yVal >= 0 && $xVal <= $rVal / 2 && $yVal <= $rVal;
}
function calcCircle($xVal, $yVal, $rVal){
    return $xVal <= 0 && $yVal <= 0 && sqrt($xVal * $xVal + $yVal * $yVal) <= $rVal;
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
$isHit = $isValid ? calcHit($xVal, $yVal, $rVal) : 'You got mail!';
$converted_isHit = $isHit ? 'true' : 'false';

$cTime = date('D, d M Y H:i:s', time() - $timezone * 60);
$exTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);

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
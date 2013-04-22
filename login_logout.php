<?php
require_once 'php-webdriver';
$wd = new WebDriver();
$session = $wd->session();

function cookies_contain($cookies, $name) {
    foreach ($cookies as $arr) {
        if ($arr['name'] == $name) {
            return true;
        }
    }
    return false;
}

function get_cookie($cookies, $name) {
    foreach ($cookies as $arr) {
        if ($arr['name'] == $name) {
            return $arr;
        }
    }
    return false;
}

function alert_present($session) {
    try {
        $session->alert_text();
        return true;
    } catch (NoAlertOpenWebDriverError $e) {
       return false;
    }
}

function split_keys($toSend){
    $payload = array("value" => preg_split("//u", $toSend, -1, PREG_SPLIT_NO_EMPTY));
    return $payload;
}

$session->open("http://flowtrend.pazion.net/nl/home");
$session->element("link text", "Inloggen")->click();
$session->element("xpath", "//form[@id='loginForm']/table")->click();
$session->element("id", "email")->click();
$session->element("id", "email")->clear();
$session->element("id", "email")->value(split_keys("peter@pazion.nl"));
$session->element("id", "password")->click();
$session->element("id", "password")->clear();
$session->element("id", "password")->value(split_keys("noizap12"));
$session->element("xpath", "//a[@id='login']//span[normalize-space(.)='Inloggen']")->click();
if ($session->element("xpath", "//div[@class='container']/div[5]/section/header/h1")->text != "Your account") {
    $session->close();
    throw new Exception("!assertText failed");
}
$session->element("xpath", "//div[@class='container']//a[normalize-space(.)='Uitloggen']")->click();
if ($session->element("xpath", "//div[@class='container']//a[normalize-space(.)='Inloggen']")->text != "Inloggen") {
    $session->close();
    throw new Exception("!assertText failed");
}

$session->close();
?>
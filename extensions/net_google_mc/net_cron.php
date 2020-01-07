<?php
/**
 * Cron Script for Google Merchant Center
 * Author: lexx
 */

/* - - - - - - - - - - please edit your values here - - - - - - - - - - - - - - */

// URL to shop administration incl. /index.php
$AdminUrl = "http://<URL ZUM SHOP>/admin/index.php";

// Shop Admin Login
$aLogin = array(
    "user" => "<OXID ADMIN EMAIL ADRESSE>",
    "pwd" => "<OXID ADMIN PASSWORT>"
);

// .htaccess user and password if shop admin is behind .htaccess prompt
$username = "";
$password = "";

$iSleepTime = 1;

/* - - - - - - - - - - DO NOT EDIT BELOW THIS LINE - - - - - - - - - - - - - - */
$aData = array(
    "cl" => "allexport_main",
    "cron" => "1"
);
$aLogin["cl"] = "login";
$aLogin["fnc"] = "checklogin";
$aLogin["chlanguage"] = "0";
$aLogin["profile"] = "0";
// Curl handle initialisation
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($ch, CURLOPT_URL, $AdminUrl);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if ($username != "" && $password != "") curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$response = curl_exec($ch);
// security token auslesen
if (preg_match(@'/<input type="hidden" name="stoken" value="([^\"]*)"/', $response, $aHits)) {
    $sToken = $aHits[1];
}
// ssid auslesen
if (preg_match(@'/<input type="hidden" name="force_admin_sid" value="([^\"]*)"/', $response, $aHits)) {
    $sSID = $aHits[1];
}
echo date("Y-m-d H:i:s") . " : Google Merchant Center Gesamt-Export Cronjob with stoken=$sToken adminSID=$sSID started.\n";
$aLogin["stoken"] = $sToken; // add stoken
$aLogin["force_admin_sid"] = $sSID; // add adminSID
curl_setopt($ch, CURLOPT_POST, true); // set to post mode
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aLogin));
$response = curl_exec($ch); // do login
//Now we are logged in
$aData["stoken"] = $sToken; // attach stoken to post request
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aData));
$response = curl_exec($ch);
if (preg_match(@'/<input type="hidden" name="editval\[iStart\]" value="([^\"]*)"/', $response, $aHits)) $iStart = $aHits[1];
if (preg_match(@'/<input type="hidden" name="editval\[iStop\]" value="([^\"]*)"/', $response, $aHits)) $iStop = $aHits[1];
if (preg_match(@'/<input type="hidden" name="editval\[netcampaigns__oxid\]" value="([^\"]*)"/', $response, $aHits)) $netcampaigns__oxid = $aHits[1];
if (preg_match(@'/<input type="hidden" name="oxid" value="([^\"]*)"/', $response, $aHits)) $oxid = $aHits[1];
if (preg_match(@'/<input type="hidden" name="campaignsid" value="([^\"]*)"/', $response, $aHits)) $campaignsid = $aHits[1];
if (preg_match(@'/<input type="hidden" name="count" value="([^\"]*)"/', $response, $aHits)) $count = $aHits[1];
$aData['editval']['iStart'] = $iStart;
$aData['editval']['iStop'] = $iStop;
$aData['editval']['netcampaigns__oxid'] = $netcampaigns__oxid;
$aData['oxid'] = $oxid;
$aData['campaignsid'] = $campaignsid;
$aData['count'] = $count;
$aData['fnc'] = "netgenexport";
while ($iStart != "finished") { // do the magic ticks
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aData));
    $response = curl_exec($ch);
    sleep($iSleepTime);
    if (preg_match(@'/<input type="hidden" name="editval\[iStart\]" value="([^\"]*)"/', $response, $aHits)) $iStart = $aHits[1];
    if (preg_match(@'/<input type="hidden" name="editval\[iStop\]" value="([^\"]*)"/', $response, $aHits)) $iStop = $aHits[1];
    if (preg_match(@'/<input type="hidden" name="editval\[netcampaigns__oxid\]" value="([^\"]*)"/', $response, $aHits)) $netcampaigns__oxid = $aHits[1];
    if (preg_match(@'/<input type="hidden" name="oxid" value="([^\"]*)"/', $response, $aHits)) $oxid = $aHits[1];
    if (preg_match(@'/<input type="hidden" name="campaignsid" value="([^\"]*)"/', $response, $aHits)) $campaignsid = $aHits[1];
    if (preg_match(@'/<input type="hidden" name="count" value="([^\"]*)"/', $response, $aHits)) $count = $aHits[1];
    $aData['editval']['iStart'] = $iStart;
    $aData['editval']['iStop'] = $iStop;
    $aData['editval']['netcampaigns__oxid'] = $netcampaigns__oxid;
    $aData['oxid'] = $oxid;
    $aData['campaignsid'] = $campaignsid;
    $aData['count'] = $count;
}
//close curl connection
curl_close($ch);
echo date("Y-m-d H:i:s") . " : Google Merchant Center Gesamt-Export Cronjob has finished.\n";
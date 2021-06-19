<?php
if ($_GET['cnt'] == 'loadips'){
  $varAccess = file_get_contents('/var/log/apache2/access.log');
  $regex = '([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})';
  //$matches
  $var = preg_match_all($regex, $varAccess, $matches);
  $arrayips = array();
    foreach($matches[0] as $ip){
      if (! in_array($ip, $arrayips)){
        array_push($arrayips, $ip);
      }
    }
  $ipsout = '';
  foreach($arrayips as $ipstring){
    $ipsout = $ipsout . $ipstring . '<br>';
  }
  print($ipsout);
}

if ($_GET['cnt'] == 'clearlog'){
  $stringnull = '';
  file_put_contents('/var/log/apache2/access.log', $stringnull);
}
if ($_GET['cnt'] == 'getwhois'){
  $ip = $_GET['ip'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://whois.toolforge.org/gateway.py?lookup=true&ip=' . $ip);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //very important
  $dat = curl_exec($ch);

  $subarr = array();
  $ipname1 = explode("nets</th><td>", $dat);
  $ipname2 = explode('</table>', $ipname1[1]);
  $ipname = $ipname2[0] . '</table><br><br>';
  print('<h3>' . $ip . '</h3>' . $ipname);
}
 ?>

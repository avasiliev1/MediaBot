#!/usr/bin/php -q 
<?php
require('phpagi.php'); 
$agi = new AGI();
$audio = $argv[1];
$token = 'd1d11b29-f0c5-41b4-9e73-33c7dbf9b24d';
$theme = "queries";
$lang = "ru-RU";
$uuid = md5(uniqid(rand(), true));
system('sox '.$audio.'.wav -r 16000 -b 16 -c 1 '.$audio.'-conv.wav');
$cmd = exec('curl --silent -F "Content-Type=audio/x-pcm;bit=16;rate=16000;" -F "audio=@'.$audio.'-conv.wav" asr.yandex.net/asr_xml\?key='.$token.'\&uuid='.$uuid .'\&topic='.$theme.'\&lang='.$lang, $result);
$result_asr = implode($result);
if (preg_match('!<variant .*?>(.*)</variant>!si', $result_asr, $arr)) {
$asr_res = $arr[1];
} else {
$asr_res='';
}
if (intval(substr_count($asr_res, 'хочу кредит')) > 0) {
$ress = 1;
} elseif (intval(substr_count($asr_res, 'хочу вклад')) > 0) {
$ress = 2;
} elseif (intval(substr_count($asr_res, 'захватить мир')) > 0) {
$ress = 3;
} elseif (intval(substr_count($asr_res, 'кто ты')) > 0) {
$ress = 4; 
} else {
$ress = 0;
}
$agi->set_variable("asr", $ress);
system('rm -f '.$audio.'.wav');
system('rm -f '.$audio.'-conv.wav');
?>

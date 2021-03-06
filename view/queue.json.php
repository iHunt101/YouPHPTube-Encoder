<?php
require_once dirname(__FILE__) . '/../videos/configuration.php';
require_once '../objects/Encoder.php';
require_once '../objects/Streamer.php';
require_once '../objects/Login.php';
header('Content-Type: application/json');
$rows = Encoder::getAll(true);
foreach ($rows as $key=>$value) {
    $f = new Format($rows[$key]['formats_id']);
    $rows[$key]['format']= $f->getName();
    $s = new Streamer($rows[$key]['streamers_id']);
    $rows[$key]['streamer']= $s->getSiteURL();
    $rows[$key]['mp4_filesize']= filesize("{$global['systemRootPath']}videos/{$rows[$key]['id']}_tmpFile_converted.mp4");
    $rows[$key]['mp4_filesize_human']= humanFileSize($rows[$key]['mp4_filesize']);
    $rows[$key]['webm_filesize']= filesize("{$global['systemRootPath']}videos/{$rows[$key]['id']}_tmpFile_converted.webm");
    $rows[$key]['webm_filesize_human']= humanFileSize($rows[$key]['webm_filesize']);
}
$rows = array_values($rows);
$total = Encoder::getTotal(true);

if(empty($_POST['rowCount']) && !empty($total)){
    $_POST['rowCount'] = $total;
}

echo '{  "current": '.$_POST['current'].',"rowCount": '.$_POST['rowCount'].', "total": '.($total).', "rows":'. json_encode($rows).'}';
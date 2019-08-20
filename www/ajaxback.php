<!--
#
# Copyright (c) 2019 Mark Bradley
# 
# This file is part of rpispeedlog
# https://github.com/mtbradley/rpispeedlog
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or 
# any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
-->
<?php
$pdo = new PDO('sqlite:/home/pi/rpispeedlog/speedresults.db');
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$queryDate = $_GET['date'];
$stmt = $pdo->prepare('SELECT * FROM results where date = :date');
$stmt->bindParam(':date', $queryDate, PDO::PARAM_STR, 8);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_NUM);

$len = count($rows);

foreach ($rows as $row) {
    $avgPing += floatval($row[3]);
    $avgDownload += floatval($row[4]);
    $avgUpload += floatval($row[5]);
}

$avgPing     = $avgPing / $len;
$avgDownload = $avgDownload / $len;
$avgUpload   = $avgUpload / $len;
echo '<div class="row justify-content-center">';
echo '<div class="col-12 text-center">';
echo '<h4 class="bold700">Speedtest Results ' . $queryDate . '</h4></div></div>';
echo '<div class="row justify-content-center">';
echo '<div class="col-12 text-center">';
echo '<h6>Average download ' . number_format((float) $avgDownload, 0, '.', '') . 'Mbps</h6></div></div>';
echo '<div class="row justify-content-center">';
echo '<div class="col-12 text-center">';
echo '<h6>Average upload ' . number_format((float) $avgUpload, 0, '.', '') . 'Mbps</h6></div></div>';
echo '<div class="row justify-content-center">';
echo '<div class="col-12 text-center">';
echo '<h6>Average ping ' . number_format((float) $avgPing, 0, '.', '') . 'ms</h6></div></div>';

$imageFile = str_replace('/', '', $rows[0][1]);
$imageFile .= ".png";
echo '<div class="row">';
echo '<div class="col-12 align-self-center">';
echo '<div class="text-center">';
echo '<img src="plots/' . $imageFile . '" class="img-fluid mb-3" />';
echo '</div>';

$i = 0;
foreach ($rows as $row) {
    $resultID       = $row[0];
    $resultDate     = $row[1];
    $resultTime     = $row[2];
    $resultPing     = $row[3];
    $resultDownload = $row[4];
    $resultUpload   = $row[5];
    if ($i == 0) {
        echo '<p class="mb-1"><strong>Log Speed Results</strong></p>';
        echo '<div class="table-responsive-sm">';
        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">Date</th>';
        echo '<th scope="col">Time</th>';
        echo '<th scope="col">Ping</th>';
        echo '<th scope="col">Download</th>';
        echo '<th scope="col">Upload</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo '<td>' . $resultDate . '</td>';
        echo '<td>' . $resultTime . '</td>';
        echo '<td>' . $resultPing . '</td>';
        echo '<td>' . $resultDownload . '</td>';
        echo '<td>' . $resultUpload . '</td>';
        echo '</tr>';
    } else {
        echo '<tr>';
        echo '<td>' . $resultDate . '</td>';
        echo '<td>' . $resultTime . '</td>';
        echo '<td>' . $resultPing . '</td>';
        echo '<td>' . $resultDownload . '</td>';
        echo '<td>' . $resultUpload . '</td>';
        echo '</tr>';
    }
    $i++;
}

echo '</tbody>';
echo '</table>';
echo '</div></div></div>';
?>

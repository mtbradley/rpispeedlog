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
$stmt = $pdo->prepare("SELECT date FROM results");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
$uniRows = array_combine(range(1, count(array_unique($rows))), array_values(array_unique($rows)));
$stmt = null;
$pdo = null;
function cmp($a, $b)
{
$a_int = DateTime::createFromFormat('d/m/y', $a)->getTimestamp();
$b_int = DateTime::createFromFormat('d/m/y', $b)->getTimestamp();
return ($a_int == $b_int) ? 0 : ($a_int < $b_int) ? 1 : -1;
}
usort($uniRows, "cmp");
$latest = $uniRows[0];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>RPi SpeedLog</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
      .branding{
        font-weight: 700;
        letter-spacing: -1px;
      }
      .bold700{
        font-weight: 700;
      }
      .bluebg {
        background: #009be2;
      }
      .chart img {
        margin: auto;
        display: block;
      }
      .debugsetup{
        border: 2px solid red;
      }
      .debugsetup2{
        border: 2px solid blue;
      }
    </style>
  </head>
  <body onload="showResult('<?php echo $latest ?>')">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
      <div class="container">
        <a class="navbar-brand branding" href="#">RPi SpeedLog</a>
      </div>
    </nav>
    <!-- Page Content -->
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form action="">
            <div class="input-group mb-3 mt-3">
              <select name="resultdate" onchange="showResult(this.value)" class="custom-select">
                <?php
$i = 0;
foreach($uniRows as $row) {
if ($i == 0) {
print "<option value='".$row."' selected>$row</option>";
} else {
print "<option value=".$row.">$row</option>";
}
++$i;
}
?>
              </select>
              <div class="input-group-append">
                <label class="input-group-text">Date
                </label>
                </form>
            </div>
            </div>
        </div>
      </div>
      <br>
      <div id="resultData">Result data will display here...
      </div>
      <script>
        function showResult(str) {
          var xhttp;
          if (str == "") {
            document.getElementById("resultData").innerHTML = "";
            return;
          }
          xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("resultData").innerHTML = this.responseText;
            }
          };
          xhttp.open("GET", "ajaxback.php?date="+str, true);
          xhttp.send();
        }
      </script>
    </div>
    <footer>
      <div class="footer text-center branding mb-3">RPi SpeedLog</div>
    </footer>
  </body>
</html>

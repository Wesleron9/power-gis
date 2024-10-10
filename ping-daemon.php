<?php

function deamon() {
  $db = new mysqli('localhost', 'root', '', 'power-gis');
  //echo "PING DAEMON RUNNING\n" . "PID: " . getmypid();
  $start = microtime(true); // Начало отсчета времени
  $data = $db->query("SELECT `id`, `ip` FROM `sensors`");

  foreach ($data as $data_row) {
    exec("ping -n 1 -w 500 -l 4 " . $data_row["ip"], $output, $result);

    if ($result == 0) {
      $db->query("UPDATE `sensors` SET `status`='online' WHERE `id`={$data_row["id"]}");
    } else {
      $db->query("UPDATE `sensors` SET `status`='offline' WHERE `id`={$data_row["id"]}");
    }
  }

  $offline = ($db->query("SELECT COUNT(`id`) FROM `sensors` WHERE `status`='offline'"))->fetch_row()[0];
  //echo "Объектов оффлайн за пройденный цикл: " . $offline . "\n";
  //echo (microtime(true) - $start) . " сек - время прохождения цикла\n";
  //echo "-----------------------------\n";
  $db->close();

  $start = null;
  $data = null;
  $db = null;
  $offline = null;
}

while (true) {
  deamon();
}
?>
<?php
require "DB-Config.php"; // Подключаемся к БД

  $data = $db->query("SELECT `id`, `ip` FROM `sensors`");

  foreach ($data as $data_row) {
    $db->query("UPDATE `sensors` SET `status`='default' WHERE `id`={$data_row["id"]}");

  }
echo "ALL OBJECTS 'status' IN DB WAS SET 'default'\n";

<?php
require "DB-Config.php"; // Подключаемся к БД

//Принимаем данные с фронта
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;

switch ($action) {
  case "add":
    // Добавление датчика в БД
    $db->query("INSERT INTO `sensors` (`latlng`, `ip`, `status`) VALUES ('$data->latlng', '$data->ip', 'default')");
    break;

  case "remove":
    // Удаление сенсера из БД
    $db->query("DELETE FROM arm WHERE `sensors`.`name` = '$data->name'");
    break;
  
    case "get-data":
    // Получение данных из БД
    $data = $db->query("SELECT `id`, `name`, `latlng`, `ip`, `status`, `isActive`  FROM `sensors`");
    $sensors = [];
    foreach ($data as $data_row) {
      array_push($sensors, [
        "id" => $data_row["id"],
        "name" => $data_row["name"],
        "latlng" => $data_row["latlng"],
        "ip" => $data_row["ip"],
        "status" => $data_row["status"],
        "isActive" => $data_row["isActive"]
      ]);
    }
    echo json_encode($sensors);
    break;
  
    case "update-data":
    // Обновление данных в БД
    $db->query("UPDATE `sensors` SET `name`='{$data->name}', `latlng`='{$data->latlng}', `ip`='{$data->ip}', `isActive`='{$data->isActive}' WHERE `id`='{$data->id}'");
    break;
}
const $sensors_list = document.querySelector("table.sensors-list")

function getAllSensors() {
  $sensors_list.innerHTML = `
  <tr>
    <td><b>ID</b></td>
    <td><b>NAME</b></td>
    <td><b>LatLng</b></td>
    <td><b>IP-адресс</b></td>
    <td><b>Статус</b></td>
    <td><b>Use</b></td>
  </tr>
  `

  // Получаем список сенсеров
  SendRequest("POST", "data.php", { action: "get-data" }, (response) => {
    response = JSON.parse(response)

    response.forEach((el) => {
      let isActive = ""
      if (el.isActive === "true") {
        isActive = "checked"
      }
      $sensors_list.insertAdjacentHTML(
        "beforeend",
        `
    <tr>
      <td>${el.id}</td>
      <td>${el.name}</td>
      <td>${el.latlng}</td>
      <td>${el.ip}</td>
      <td>${el.status}</td>
      <td><input type="checkbox" ${isActive}></td>
    </tr>
    `
      )
    })
  })
}

document.querySelector("button.save").addEventListener("click", () => {
  $sensors_list.querySelectorAll("tr").forEach(($tr, index) => {
    if (index === 0) return

    let cells = $tr.querySelectorAll("td")

    let id = cells[0].innerText
    let name = cells[1].innerText
    let latlng = cells[2].innerText
    let ip = cells[3].innerText
    let isActive = cells[5].querySelector("input").checked.toString()

    SendRequest(
      "POST",
      "data.php",
      {
        action: "update-data",
        isActive: isActive,
        id: id,
        name: name,
        latlng: latlng,
        ip: ip,
      },
      (response) => {
        console.log(response)
      }
    )
  })
})

getAllSensors()

// init map
let map = L.map("map", {
  center: [44.701, 37.763],
  zoom: 12,
})

// Base layer
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map)

// Updating data
let sensors = new L.LayerGroup()

function updateData() {
  SendRequest("POST", "data.php", { action: "get-data" }, (response) => {
    response = JSON.parse(response)

    map.removeLayer(sensors)

    sensors = new L.LayerGroup()

    response.forEach((el) => {
      if (el.status === "default" || el.status === "online" || el.isActive === "false") return

      new L.marker(el.latlng.split(", "), {
        icon: L.divIcon({
          iconSize: [40, 40],
          html: `
      <div class="marker">
      <img src="images/power-warn.svg" style="width:40px;">
      <p style="color: #EB4C42">${el.name}</p>
      </div>
      `,
        }),
      }).addTo(sensors)
    })

    sensors.addTo(map)
  })
}

// Update interval
setInterval(updateData, 500)

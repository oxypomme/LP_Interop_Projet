// Init current day
const items = document.querySelectorAll(".meteo--item.item-passed");
for (let i = 0; i < items.length; i++) {
	if (i < items.length - 1) {
		items[i].classList.remove("item-passed");
	}
}

// Init map
if (geoloc) {
	const map = L.map("map").setView(geoloc.latlng, 13);
	L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
		attribution:
			'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
	}).addTo(map);
	L.marker(geoloc.latlng).addTo(map).bindPopup("Position actuelle").openPopup();
	if (velos) {
		for (const velo of velos) {
			L.marker(velo.latlng).addTo(map).bindPopup(`
          ${velo.name}<br/>
          ${velo.address}<br/>
          <br/>
          Places libres: ${velo.free}<br/>
          Vélos: ${velo.available}`);
		}
	}
}

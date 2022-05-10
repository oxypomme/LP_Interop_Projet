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

	// Actual position
	const actMarker = L.marker(geoloc.latlng).addTo(map);
	actMarker.bindPopup("Position actuelle").openPopup();
	L.DomUtil.addClass(actMarker._icon, "map--center");

	// IUT position
	const iutMarker = L.marker([48.68287641100645, 6.161284933811825]).addTo(map);
	iutMarker.bindPopup("IUT Nancy Charlemagne");
	L.DomUtil.addClass(iutMarker._icon, "map--iut");

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

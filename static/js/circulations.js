// Init map
if (ndlLatLng) {
	const map = L.map("map").setView(ndlLatLng, 13);
	L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
		attribution:
			'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
	}).addTo(map);
	const ndlMarker = L.marker(ndlLatLng).addTo(map);
	ndlMarker.bindPopup("Mairie de Notre-Dame-des-Landes").openPopup();
	L.DomUtil.addClass(ndlMarker._icon, "map--center");
}

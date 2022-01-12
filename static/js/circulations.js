// Init map
if (ndlLatLng) {
	const map = L.map("map").setView(ndlLatLng, 10);
	L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
		attribution:
			'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
	}).addTo(map);
	const ndlMarker = L.marker(ndlLatLng).addTo(map);
	ndlMarker.bindPopup("Mairie de Notre-Dame-des-Landes").openPopup();
	L.DomUtil.addClass(ndlMarker._icon, "map--center");

	if (infos) {
		for (const info of infos) {
			L.marker(info.latlng).addTo(map).bindPopup(`
          ${info.location}<br/>
          ${info.nature}<br/>
          ${info.type}<br/>
          ${info.duration}<br/>`);
		}
	}
}

// Init graphs
if (covid) {
	const datasets = {};
	for (const day of covid) {
		for (const [key, value] of Object.entries(day)) {
			if (value) {
				datasets[key] = [...(datasets[key] ?? []), value];
			}
		}
	}
	const config = (title) => ({
		plugins: {
			legend: {
				position: "bottom",
			},
			title: {
				display: true,
				text: title,
			},
		},
	});

	// Incidence
	new Chart(document.getElementById("graph-tx_incid"), {
		type: "line",
		options: {
			...config("Taux d'incidence (30 derniers jours)"),
			scales: {
				y: {
					min: 0,
				},
			},
		},
		data: {
			labels: datasets.date,
			datasets: [
				{
					label: "Taux d'incidence",
					data: datasets.tx_incid,
					backgroundColor: "rgb(0, 125, 255)",
					borderColor: "rgb(0, 125, 255)",
				},
			],
		},
	});

	// Hospitalisation
	new Chart(document.getElementById("graph-hosp"), {
		type: "line",
		options: {
			...config(
				"Nombres d'hospitalisations et de réaimations (30 derniers jours)"
			),
		},
		data: {
			labels: datasets.date,
			datasets: [
				{
					label: "Nombre d'hospitalisations",
					data: datasets.hosp,
					backgroundColor: "rgb(0, 125, 255)",
					borderColor: "rgb(0, 125, 255)",
				},
				{
					label: "Nombre de réanimations",
					data: datasets.rea,
					backgroundColor: "rgb(255, 0, 125)",
					borderColor: "rgb(255, 0, 125)",
				},
			],
		},
	});
}

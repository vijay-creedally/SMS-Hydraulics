/* Add block specific JS here */

import jsVectorMap from 'jsvectormap'
import 'jsvectormap/dist/jsvectormap.min.css'
import 'jsvectormap/dist/maps/world.js'

document.addEventListener("DOMContentLoaded", function () {

	const mapBlocks = document.querySelectorAll(".project-map");

	mapBlocks.forEach((wrapper) => {
		const parent = wrapper.parentElement;
		const mapId = `#${wrapper.id}`;
		const detailPanel = parent.querySelector(".project-details");
		let country_code = wrapper?.getAttribute("data-default-country-code");

		let markers = [];

		// Parse JSON
		try {
			const data = JSON.parse(wrapper.getAttribute("data-map"));
			markers = data.map((item) => ({
				name: item.name,
				coords: [
					parseFloat(item.coords[0]),
					parseFloat(item.coords[1]),
				],
				country_code: item.country_code ?? "",
			}));
		} catch (e) {
			console.error("Invalid JSON in data-map", e);
		}

		// Init map
		const mapInstance = new jsVectorMap({
			selector: mapId,
			map: "world",
			responsive: true,
			zoomOnScroll: false,
			zoomButtons: true,
			panOnDrag: true,
			markers,
			showTooltipOn: "marker",
			regionStyle: {
				initial: {
					fill: "#EBE5F0",
				}
			},
			markerStyle: {
				initial: {
					r: 8,
					fill: "rgba(38, 51, 116, 1)",
					strokeWidth: 2,
					stroke: "rgba(38, 51, 116, 1)",
					cursor: "pointer",
				},
				hover: {
					stroke: "#FFF",
					strokeWidth: 2,
					fill: "rgba(212, 18, 29, 1)",
					cursor: "pointer",
				},
			},
			onMarkerTooltipShow(event, tooltip, index) {
				tooltip.css({ position: 'absolute', 'display': 'block', backgroundColor: '#FFF', color: '#000', fontSize: '16px', fontWeight: '800', padding: '6px 14px', letterSpacing: '8px', boxShadow: '0 5px 10px rgba(172, 166, 184, 0.50)', });
				tooltip.text(markers[index].name.toUpperCase());
			},
			onMarkerClick(event, index) {
				event.preventDefault();
				detailPanel.classList.remove("ani-left");
				
				let marker = markers[index];
				let country = marker?.name;
				let country_code = marker?.country_code;

				resizeMap(mapInstance, parent, true, country_code);

				detailPanel.innerHTML = '';
				detailPanel.classList.add("loading");
				fetch(smsObj.ajaxurl + '?action=fetch_project_details&country_code=' + country_code+'&country='+encodeURIComponent(country))
				.then(res => res.json())
				.then(response => {
					if (response.success) {
						detailPanel.classList.remove("loading");
						detailPanel.innerHTML = response.data;
						cardCloseHandler(mapInstance, parent);
					} else {
						console.error("Error fetching project details:", response.data.message);
						detailPanel.classList.remove("loading");
						detailPanel.innerHTML = '<div class="notifications"><p class="notification error"><strong>Error:</strong>'+response.data.message+'</p></div>';
						resizeMap(mapInstance, parent, false, country_code);
					}
				})
				.catch(err => {
					console.error("Request Error:", err);
					detailPanel.classList.remove("loading");
					resizeMap(mapInstance, parent, false, country_code);
					detailPanel.innerHTML = '<div class="notifications"><p class="notification error"><strong>Error:</strong>'+err+'</p></div>';
				});
			},
			onRegionTooltipShow(event, tooltip) {
				event.preventDefault();
				tooltip.css({ position: 'absolute', 'display': 'none'});
				tooltip.hide();
			},
			onRegionClick(event) {
				event.preventDefault();
			},
		});

		let onLoadCountry = ( window.innerWidth < 1024 ) ? country_code : '';
		resizeMap(mapInstance, parent, false, onLoadCountry);
	
		window.addEventListener("resize", () => {
			resizeMap(mapInstance, parent, false, country_code);
		});
	});

	function cardCloseHandler(mapInstance, parent) {
		document.querySelectorAll('.project__flag--close').forEach(closeBtn => {
			closeBtn.addEventListener('click', (e) => {
				const detailPanel = parent.querySelector(".project-details");
				const projectMap = parent.querySelector(".project-map");
				let country = projectMap?.getAttribute("data-default-country-label");
				let country_code = projectMap?.getAttribute("data-default-country-code");
				resizeMap(mapInstance, parent, false, country_code);
				detailPanel.innerHTML = '';
				detailPanel.classList.add("loading");
				fetch(smsObj.ajaxurl + '?action=fetch_project_details&country_code=' + country_code+'&country='+encodeURIComponent(country))
				.then(res => res.json())
				.then(response => {
					if (response.success) {
						detailPanel.classList.remove("loading");
						detailPanel.innerHTML = response.data;
						cardCloseHandler(mapInstance, parent);
					} else {
						console.error("Error fetching project details:", response.data.message);
						detailPanel.classList.remove("loading");
						detailPanel.innerHTML = '<div class="notifications"><p class="notification error"><strong>Error:</strong>'+response.data.message+'</p></div>';
						resizeMap(mapInstance, parent, false, country_code);
					}
				})
				.catch(err => {
					console.error("Request Error:", err);
					detailPanel.classList.remove("loading");
					resizeMap(mapInstance, parent, false, country_code);
					detailPanel.innerHTML = '<div class="notifications"><p class="notification error"><strong>Error:</strong>'+err+'</p></div>';
				});
			});
		});
	}

	function resizeMap(mapInstance, parent, isResize = false, country_code = '') {
		const mapPanel = parent.querySelector(".project-map");
		const detailPanel = parent.querySelector(".project-details");

		if (!mapPanel || !detailPanel) {
			console.warn("Missing .project-map or .project-details element");
			return;
		}

		let mapWidth = "calc(70% - 1.25rem)";
		let mapHeight = "650px";
		let detailWidth = "calc(30% - 1.25rem)";
		let detailPanelDisplay = "block";

		// Desktop: Split view after marker click
		if (isResize && window.innerWidth >= 1024) {
			mapWidth = "calc(70% - 1.25rem)";
			detailWidth = "calc(30% - 1.25rem)";
			detailPanelDisplay = "block";
		}

		// Mobile: Always full-width map and full-width details
		if (window.innerWidth < 1024) {
			mapWidth = "100%";
			mapHeight = "300px";
			detailWidth = "100%";

			// On mobile, only show details after click
			if (isResize) {
				detailPanelDisplay = "block";
			}
		}

		// Apply CSS
		mapPanel.style.width = mapWidth;
		mapPanel.style.height = mapHeight;

		detailPanel.style.width = detailWidth;
		detailPanel.style.display = detailPanelDisplay;

		// Ensure map redraws smoothly
		setTimeout(() => {
			mapInstance.updateSize();

			if(country_code) {
				mapInstance.setFocus({
					region: country_code.toUpperCase(),
					animate: true
				});
			}
		}, 200);
	}
});

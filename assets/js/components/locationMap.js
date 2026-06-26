import jsVectorMap from 'jsvectormap'
import 'jsvectormap/dist/jsvectormap.min.css'
import 'jsvectormap/dist/maps/world.js'

const mapBlocks = document.querySelectorAll(".project-map");

mapBlocks.forEach((wrapper) => {
    const parent = wrapper.parentElement;
    const mapId = `#${wrapper.id}`;
    const detailPanel = parent.querySelector(".project-details");

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
        zoomButtons: false,
        markers,
        regionStyle: {
            initial: {
                fill: "#EBE5F0",
            }
        },
        markerStyle: {
            initial: {
                r: 6,
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
            tooltip.css({ backgroundColor: '#FFF', color: '#000', fontSize: '16px', fontWeight: '800', padding: '6px 14px', letterSpacing: '8px', boxShadow: '0 5px 10px rgba(172, 166, 184, 0.50)', });
            tooltip.text(markers[index].name.toUpperCase());
        },
        onMarkerClick(event, index) {
            event.preventDefault();
            detailPanel.classList.remove("ani-left");

            resizeMap(mapInstance, parent, true);

            let marker = markers[index];
            let country = marker?.name;
            let country_code = marker?.country_code;
            
            fetch(smsObj.ajaxurl + '?action=fetch_project_details&country_code=' + country_code+'&country='+encodeURIComponent(country))
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    detailPanel.innerHTML = response.data;
                    cardCloseHandler(mapInstance, parent);
                } else {
                    console.error("Error fetching project details:", response.data.message);
                }
            })
            .catch(err => console.error("Request Error:", err));

        },
        onRegionTooltipShow(event, tooltip) {
            event.preventDefault();
            tooltip.css({
                backgroundColor: '#FFF',
                color: '#000',
                fontWeight: 'bold',
                padding: '10px 20px',
                borderRadius: '5px',
                letterSpacing: '8px',
                boxShadow: '0 2px 6px rgba(0,0,0,0.3)',
            });
        },
        onRegionClick(event) {
            event.preventDefault();
        },
    });

    resizeMap(mapInstance, parent);

    window.addEventListener("resize", () => {
        
        resizeMap(mapInstance, parent);
    });
});

function cardCloseHandler(mapInstance, parent) {
    document.querySelectorAll('.project__flag--close').forEach(closeBtn => {
        closeBtn.addEventListener('click', (e) => {
            resizeMap(mapInstance, parent, false);
        });
    });
}

function resizeMap(mapInstance, parent, isResize = false) {
    const mapPanel = parent.querySelector(".project-map");
    const detailPanel = parent.querySelector(".project-details");

    if (!mapPanel || !detailPanel) {
        console.warn("Missing .project-map or .project-details element");
        return;
    }

    let mapWidth = "100%";
    let mapHeight = "650px";
    let detailWidth = "100%";
    let detailPanelDisplay = "none";

    // Desktop: Split view after marker click
    if (isResize && window.innerWidth >= 992) {
        mapWidth = "calc(70% - 1.25rem)";
        detailWidth = "calc(30% - 1.25rem)";
        detailPanelDisplay = "block";
    }

    // Mobile: Always full-width map and full-width details
    if (window.innerWidth < 992) {
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
    }, 150);
}

@php
    $mapId = $mapId ?? 'pos-locations-map';
    $locations = $locations ?? [];
    $mapTitle = $mapTitle ?? 'POS locations';
    $mapDescription = $mapDescription ?? 'Explore POS locations on the map. Select a marker to view details and directions.';
    $coordinatePicker = $coordinatePicker ?? false;
    $pickerEnabled = $pickerEnabled ?? $coordinatePicker;
    $latitudeInputId = $latitudeInputId ?? null;
    $longitudeInputId = $longitudeInputId ?? null;
@endphp

<section class="pos-map-panel" aria-labelledby="{{ $mapId }}-title">
    <div class="pos-map-panel__header">
        <div>
            <span class="pos-map-panel__eyebrow">Interactive map</span>
            <h2 id="{{ $mapId }}-title">{{ $mapTitle }}</h2>
            <p>{{ $mapDescription }}</p>
        </div>
        <span class="pos-map-panel__count" id="{{ $mapId }}-count" aria-live="polite">Loading map</span>
    </div>
    <div id="{{ $mapId }}" class="pos-map-canvas {{ $coordinatePicker ? 'pos-map-canvas--picker' : '' }}" role="application" aria-label="Interactive map of POS locations"></div>
    <p class="pos-map-panel__status" id="{{ $mapId }}-status" aria-live="polite">Loading map tiles and POS locations…</p>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        const mapId = @json($mapId);
        const coordinatePicker = @json($coordinatePicker);
        let pickerEnabled = @json($pickerEnabled);
        const latitudeInputId = @json($latitudeInputId);
        const longitudeInputId = @json($longitudeInputId);
        const canvas = document.getElementById(mapId);
        const status = document.getElementById(mapId + '-status');
        const count = document.getElementById(mapId + '-count');
        if (!canvas) return;

        if (typeof window.L === 'undefined') {
            status.textContent = 'The map could not load. Check your internet connection and try again.';
            count.textContent = 'Map unavailable';
            return;
        }

        const map = L.map(canvas, { scrollWheelZoom: false }).setView([20.5937, 78.9629], 5);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        const markerLayer = L.featureGroup().addTo(map);
        const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[char]));

        function render(locations) {
            markerLayer.clearLayers();
            const validLocations = (Array.isArray(locations) ? locations : []).filter(location => {
                const rawLatitude = location.latitude ?? location.lat;
                const rawLongitude = location.longitude ?? location.lng;
                if (rawLatitude === null || rawLongitude === null
                    || String(rawLatitude).trim() === '' || String(rawLongitude).trim() === '') return false;
                const latitude = Number(rawLatitude);
                const longitude = Number(rawLongitude);
                return Number.isFinite(latitude) && Number.isFinite(longitude)
                    && latitude >= -90 && latitude <= 90 && longitude >= -180 && longitude <= 180;
            });

            count.textContent = validLocations.length + (validLocations.length === 1 ? ' location mapped' : ' locations mapped');
            status.textContent = validLocations.length
                ? 'Select a marker to see POS details and directions.'
                : 'No POS locations have map coordinates yet. Locations without coordinates remain available in the list.';

            validLocations.forEach(location => {
                const latitude = Number(location.latitude ?? location.lat);
                const longitude = Number(location.longitude ?? location.lng);
                const name = escapeHtml(location.name || 'POS location');
                const address = [location.address, location.city, location.state, location.zip]
                    .filter(Boolean).map(escapeHtml).join(', ');
                const phone = location.mobilenumber ? '<div>' + escapeHtml(location.mobilenumber) + '</div>' : '';
                const directions = 'https://www.google.com/maps/dir/?api=1&destination=' + latitude + ',' + longitude;
                const popup = '<div class="pos-map-popup"><strong>' + name + '</strong>'
                    + (address ? '<div>' + address + '</div>' : '') + phone
                    + '<a href="' + directions + '" target="_blank" rel="noopener noreferrer">Get directions</a></div>';
                L.marker([latitude, longitude]).bindPopup(popup).addTo(markerLayer);
            });

            if (validLocations.length === 1) {
                map.setView(markerLayer.getLayers()[0].getLatLng(), 14);
            } else if (validLocations.length > 1) {
                map.fitBounds(markerLayer.getBounds().pad(0.15), { maxZoom: 13 });
            }
            window.setTimeout(() => map.invalidateSize(), 0);
        }

        window.FreebazarPosMaps = window.FreebazarPosMaps || {};
        window.FreebazarPosMaps[mapId] = {
            update: render,
            setPickerEnabled: enabled => { pickerEnabled = coordinatePicker && Boolean(enabled); }
        };
        render(@json($locations));

        if (coordinatePicker) {
            map.on('click', event => {
                if (!pickerEnabled) return;
                const latitudeInput = document.getElementById(latitudeInputId);
                const longitudeInput = document.getElementById(longitudeInputId);
                if (!latitudeInput || !longitudeInput) return;
                const latitude = event.latlng.lat.toFixed(6);
                const longitude = event.latlng.lng.toFixed(6);
                latitudeInput.value = latitude;
                longitudeInput.value = longitude;
                render([{ name: 'Selected POS location', latitude, longitude }]);
                status.textContent = 'Location selected. Save the POS to keep these coordinates.';
            });
        }
    })();
</script>

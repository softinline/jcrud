<script src='https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js'></script>
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css' rel='stylesheet' />        
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css' type='text/css' />

<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Latitude</label>
                <input id="{{ $field['longitudeField'] }}" name="{{ $field['longitudeField'] }}" readonly=true class="form-control" value="{{ @$item->{$field['longitudeField']} }}" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Longitude</label>
                <input id="{{ $field['latitudeField'] }}" name="{{ $field['latitudeField'] }}" readonly=true class="form-control" value="{{ @$item->{$field['latitudeField']} }}"/>
            </div>
        </div>
    </div>
    <div class="form-group">                
        <div id='map-{{ $field['field'] }}' style="width:400px; height:300px"></div>
    </div>
</div>
<script>

    $(function() {

        mapboxgl.accessToken = jcrud.mapboxAccessToken;
            
        var latitude = "{{ @$item->{$field['latitudeField']} }}";
        var longitude = "{{ @$item->{$field['longitudeField']} }}";

        if(latitude == '') {
            latitude = 0;
        }
        if(longitude == '') {
            lontiude = 0;
        }

        var map = new mapboxgl.Map({
            container: 'map-{{ $field['field'] }}',
            style: 'mapbox://styles/mapbox/streets-v11'
        });

        const marker = new mapboxgl.Marker({
            draggable: true
        }).setLngLat([longitude, latitude]).addTo(map);

        map.setCenter([longitude, latitude]);
        map.setZoom(5);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            $("#{{ $field['latitudeField'] }}").val(lngLat.lat);
            $("#{{ $field['longitudeField'] }}").val(lngLat.lng);                
        } 
        
        marker.on('dragend', onDragEnd);

        const geocoder = new MapboxGeocoder({
            // Initialize the geocoder
            accessToken: mapboxgl.accessToken, // Set the access token
            mapboxgl: mapboxgl, // Set the mapbox-gl instance
            marker: false // Do not use the default marker style
        });

        geocoder.on('result', (event) => {    
            marker.setLngLat(event.result.geometry.coordinates);
            $("#{{ $field['latitudeField'] }}").val(event.result.geometry.coordinates[1]);
            $("#{{ $field['longitudeField'] }}").val(event.result.geometry.coordinates[0]);    
        });

        // Add the geocoder to the map
        map.addControl(geocoder);

    });

</script>
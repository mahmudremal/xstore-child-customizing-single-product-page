( function ( $ ) {
	class FWPListivo_Map {
		/**
		 * Constructor
		 */
		constructor() {
			this.initializeMap();
		}
		initializeMap() {
			const thisClass = this;var mapInterval, style, script;thisClass.mapID = 'fwplistivoamchartsmap';

			if( document.getElementById( thisClass.mapID ) ) {
				script = document.createElement( 'script' );script.type = 'text/javascript';// script.integrity = 'sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=';script.crossorigin = '';
				script.src = 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js';document.head.appendChild( script );
				style = document.createElement( 'link' );style.rel = 'stylesheet';// style.integrity = 'sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=';style.crossorigin = '';
				style.href = 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.css';document.head.appendChild( style );

				mapInterval = setInterval( () => {
					if( typeof L !== 'undefined' ) {
						thisClass.leafletMap();clearInterval( mapInterval );
					}
				}, 1000 );
			}
		}
		leafletMap() {
			const thisClass = this;var mapInterval, style, script, scripts, marker, circle, polygon, popup, markers = [], Icons = {};
			const map = L.map( thisClass.mapID ).setView([ 45.274886, 14.150391 ], 4);
			const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
			}).addTo(map);
			const PinIcon = L.Icon.extend({
				options: {
					shadowUrl: 		false, // 'https://leafletjs.com/examples/custom-icons/leaf-green.png',
					iconSize:     [38, 95],
					shadowSize:   [50, 64],
					iconAnchor:   [22, 94],
					shadowAnchor: [4, 62],
					popupAnchor:  [-3, -76]
				}
			});
			Icons.pinPoint = new PinIcon({iconUrl: 'https://www.svgrepo.com/show/423397/pin-point.svg'});
			Icons.pinLocat = new PinIcon({iconUrl: 'https://www.svgrepo.com/show/435064/pin-location.svg'});

			// var popup = L.marker([51.5, -0.09]).addTo(map).bindPopup('A pretty CSS3 popup.<br> Easily customizable.').openPopup();
			marker = L.marker([ 45.274886, 14.150391 ], {
				icon: Icons.pinLocat,
				alt: 'Dhaka'
			} ).addTo(map);
			// var mapInterval = setInterval( () =>{
			// 	marker.setLatLng( [51.513, -0.19] )
			// }, 1000 );
			// circle = L.circle([51.508, -0.11], {
			// 	color: 'red',
			// 	fillColor: '#f03',
			// 	fillOpacity: 0.5,
			// 	radius: 500
			// }).addTo(map);
			// polygon = L.polygon([
			// 	[51.509, -0.08],
			// 	[51.503, -0.06],
			// 	[51.51, -0.047]
			// ]).addTo(map);
			// marker.bindPopup("<b>Hello world!</b><br>I am a popup.");
			// circle.bindPopup("I am a circle.");
			// polygon.bindPopup("I am a polygon.");

			popup = L.popup(); //.setLatLng([51.513, -0.09]).setContent("I am a standalone popup.").openOn(map);
			map.on('click', function( e ) {
				markers.push( L.marker( e.latlng, {icon: Icons.pinPoint} ).addTo( map ).bindPopup("<b>This is!</b>a popup.") );
				// marker.setLatLng(e.latlng).bindPopup("<b>Location:</b><br>." + e.latlng.toString()).openPopup();
				// popup.setLatLng(e.latlng).setContent("You clicked the map at " + e.latlng.toString()).openOn(map);
				// console.log( e, e.latlng );
			} );
			map.on('locationfound', function( e ) {
				var radius = e.accuracy;
				L.marker(e.latlng).addTo(map).bindPopup("You are within " + radius + " meters from this point").openPopup();
				L.circle(e.latlng, radius).addTo(map);
			} );
			map.on( 'locationerror', function( e ) {
				marker.bindPopup( "<b>Error:</b><br>." + e.message ).openPopup();
			} );
			// map.locate({setView: true, maxZoom: 16}); // For getting current location of mine.
		}
		amchartsMap() {
			const thisClass = this;var mapInterval, style, script, scripts;
			scripts = [
				[ 'amcharts-index', 'https://cdn.amcharts.com/lib/5/index.js' ],
				[ 'amcharts-map', 'https://cdn.amcharts.com/lib/5/map.js' ],
				[ 'amcharts-animated', 'https://cdn.amcharts.com/lib/5/themes/Animated.js' ],
				[ 'amcharts-countries2', 'https://cdn.amcharts.com/lib/5/geodata/data/countries2.js' ]
			];

			scripts.forEach( function( e, i ) {
				script = document.createElement( 'script' );script.type = 'text/javascript';
				script.src = e[1];script.id = e[0];document.head.appendChild( script );
			} );
			
			mapInterval = setInterval( () =>{
				// window.am5 && am5geodata_data_countries2
				
				if( typeof am5 !== 'undefined' && typeof am5geodata_data_countries2 !== 'undefined' ) {
					console.log( am5geodata_data_countries2 );
					am5.ready(function() {
						clearInterval( mapInterval );thisClass.executeMapCanvas( am5 );
					} );
				}
			}, 1000 );
		}
		executeMapCanvas( am5 ) {
			// https://www.amcharts.com/docs/v5/getting-started/#Root_element
			var root = am5.Root.new("fwplistivoamchartsmap");
			// Set themes
			// https://www.amcharts.com/docs/v5/concepts/themes/
			root.setThemes([
				am5themes_Animated.new(root)
			]);
			// Create the map chart
			// https://www.amcharts.com/docs/v5/charts/map-chart/
			var chart = root.container.children.push(am5map.MapChart.new(root, {
				panX: "rotateX",
				projection: am5map.geoMercator(),
				layout: root.horizontalLayout
			}));
			// am5.net.load("https://www.amcharts.com/tools/country/?v=xz6Z", chart).then(function (result) {
			// 	var geo = am5.JSONParser.parse(result.response);
			// 	thisClass.loadGeodata(geo.country_code);
			// });
			thisClass.loadGeodata( 'RU', chart );
			// Create polygon series for continents
			// https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
			var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
				calculateAggregates: true,
				valueField: "value"
			}));
			polygonSeries.mapPolygons.template.setAll({
				tooltipText: "{name}",
				interactive: true
			});
			polygonSeries.mapPolygons.template.states.create("hover", {
				fill: am5.color(0x677935)
			});
		}
		loadGeodata( country, chart ) {
			// Default map
			var defaultMap = "usaLow";
			if (country == "US") {
				chart.set("projection", am5map.geoAlbersUsa());
			} else {
				chart.set("projection", am5map.geoMercator());
			}
			// calculate which map to be used
			var currentMap = defaultMap;
			var title = "";
			if (am5geodata_data_countries2[country] !== undefined) {
				currentMap = am5geodata_data_countries2[country]["maps"][0];
				// add country title
				if (am5geodata_data_countries2[country]["country"]) {
					title = am5geodata_data_countries2[country]["country"];
				}
			}
			am5.net.load("https://cdn.amcharts.com/lib/5/geodata/json/" + currentMap + ".json", chart).then(function (result) {
				var geodata = am5.JSONParser.parse(result.response);
				var data = [];
				for(var i = 0; i < geodata.features.length; i++) {
					data.push({
						id: geodata.features[i].id,
						value: Math.round( Math.random() * 10000 )
					});
				}
				polygonSeries.set("geoJSON", geodata);
				polygonSeries.data.setAll(data)
			});
			chart.seriesContainer.children.push(am5.Label.new(root, {
				x: 5,
				y: 5,
				text: title,
				background: am5.RoundedRectangle.new(root, {
					fill: am5.color(0xffffff),
					fillOpacity: 0.2
				})
			}))
		}
		images() {
			/**
			 * https://www.svgrepo.com/show/343873/shop-online-store-ecommerce.svg
			 * https://www.svgrepo.com/show/343873/shop-online-store-ecommerce.svg
			 * https://www.svgrepo.com/show/356957/shop.svg
			 * 
			 * https://www.svgrepo.com/show/448100/pin.svg
			 * https://www.svgrepo.com/show/449529/pin.svg
			 * https://www.svgrepo.com/show/447736/pin.svg
			 * https://www.svgrepo.com/show/435065/pin-location-2.svg
			 * https://www.svgrepo.com/show/435064/pin-location.svg
			 * https://www.svgrepo.com/show/423397/pin-point.svg
			 * https://www.svgrepo.com/show/423402/pin-stage.svg
			 * 
			 * https://www.svgrepo.com/show/374701/job-position.svg
			 * https://www.svgrepo.com/show/374702/job-family.svg
			 * https://www.svgrepo.com/show/170571/job-search.svg
			 * 
			 * https://www.svgrepo.com/show/408348/cart-shop-sell-buy.svg
			 */
		}
	}
	new FWPListivo_Map();
} )( ( typeof jQuery !== 'undefined' ) ? jQuery : false );

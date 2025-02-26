// Declare the map variable outside the initialize function
var map;

/* Filtre */
$(document).ready(function() {
  $('.champ_filtre').on('click', function() {
    console.log("toto")
      $('.filtre-popup').css('display', 'flex');
  });

  $('.croix_filtre').on('click', function() {
    $('.filtre-popup').css('display', 'none');
  });


});

$(document).ready(function () {
  // Utilisation de la délégation d'événements pour gérer les clics sur les éléments de classe tag-noselect
  $(document).on('click', '.tag-noselect', function () {
      console.log("no-select");

      var elementADeplacer = $(this);

      // Ajoute la classe tag-select
      elementADeplacer.removeClass('tag-noselect');
      elementADeplacer.addClass('tag-select');

      // Sélectionnez le div destination
      var divDestination = $('.tags-utilise');
      // Déplacez l'élément vers le div destination
      divDestination.append(elementADeplacer);
  });

  // Utilisation de la délégation d'événements pour gérer les clics sur les éléments de classe tag-select
  $(document).on('click', '.tag-select', function () {
      console.log("select");

      var elementADeplacer = $(this);

      // Sélectionnez le div destination
      var divDestination = $('.tags');
      // Déplacez l'élément vers le div destination
      divDestination.append(elementADeplacer);

      elementADeplacer.removeClass('tag-select');
      // Ajoute la classe tag-noselect
      elementADeplacer.addClass('tag-noselect');
  });

  createMap();
});

function initialize() {
  var logementsData = document.getElementById('logements').getAttribute('data-logements');
  var logements = JSON.parse(logementsData);

  // Check if the map is already initialized
  if (!map) {
      map = L.map('map');

      var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19
      });

      map.addLayer(osmLayer);
  }

  // Clear all existing markers
  map.eachLayer(function (layer) {
      if (layer instanceof L.Marker) {
          map.removeLayer(layer);
      }
  });

  var i=0;
  logements.forEach(function (coordonnees) {
      var latitude = parseFloat(coordonnees.latitude);
      var longitude = parseFloat(coordonnees.longitude);

      var customSvgIcon = L.divIcon({
        html: `<svg xmlns="http://www.w3.org/2000/svg" width="27.794" height="45.757" viewBox="0 0 47.794 65.757">
                <g id="Groupe_424" data-name="Groupe 424" transform="translate(1638.819 712.838)">
                    <path class="marker-map" id="${i}" data-name="Soustraction 1" d="M23.4,63.807v0l-.9-1.269c-1.767-2.478-3.681-4.982-5.65-7.54l-.088-.116-.016-.02c-1.854-2.424-3.771-4.926-5.581-7.47l-.007-.015c-.176-.249-.395-.554-.645-.906l-.284-.4-.964-1.351-.006-.009C4.79,38.484,.177,32.046,.007,23.848L0,23.4A23.4,23.4,0,0,1,39.943,6.857,23.333,23.333,0,0,1,46.794,23.4l-.007.009v.438c-.169,8.2-4.788,14.641-9.255,20.873-.129.179-.256.358-.381.535-.109.152-.216.3-.321.451-.455.64-.888,1.25-1.186,1.673l-.007.015c-1.9,2.667-3.908,5.285-5.68,7.591l-.012.015-.258.334-.112.146c-1.767,2.306-3.595,4.692-5.273,7.06L23.4,63.8Zm0-54.253a13.855,13.855,0,1,0,9.788,4.055A13.755,13.755,0,0,0,23.4,9.553Z" transform="translate(-1638.319 -712.338)" fill="#6300FF" stroke="rgba(0,0,0,0)" stroke-miterlimit="10" stroke-width="1"></path>
                </g>
              </svg>`,
        className: "",
        iconSize: [24, 40],
        iconAnchor: [14, 42],
    });  

      var marker = L.marker([latitude, longitude], { icon: customSvgIcon });
      var popupContent = "<a style='width:100%' target=”_blank” href='"+ coordonnees.link +"'>"
                        + "<img src='" + coordonnees.photos[0].url + "' alt='Description de l'image' width='100' height='100'>"
                        + "<div class='text-pop'>" + coordonnees.titre + " - <span class='bold'> " + coordonnees.ville 
                        + "</span> <br><span class='bold'>" + coordonnees.prix_nuit + " </span> € par nuit </div>"
                        + "</a>";
      marker.bindPopup(popupContent);
      marker.addTo(map);
      i++;
  });

  if (logements.length > 1) {
      var bounds = new L.LatLngBounds();

      logements.forEach(function (coordonnees) {
          var latitude = parseFloat(coordonnees.latitude);
          var longitude = parseFloat(coordonnees.longitude);
          bounds.extend([latitude, longitude]);
      });

      map.fitBounds(bounds);
  } else if (logements.length === 1) {
      map.setView([parseFloat(logements[0].latitude), parseFloat(logements[0].longitude)], 13);
  } else {
      map.setView([48.833, 2.333], 6);
  }



  /* Changement couleur marqueur */

$('.card_logement').mouseenter(function () {
  console.log("hello");
  var id = $(this).attr('id');
  $('.marker-map[id="' + id + '"]').css('fill', 'red'); // Change background color, for example
});

$('.card_logement').mouseleave(function () {
  var id = $(this).attr('id');
  $('.marker-map[id="' + id + '"]').css('fill', '#6300FF'); // Reset background color
});

$('.owl-prev').click(function (event) {
  event.preventDefault();
});

$('.owl-next').click(function (event) {
  event.preventDefault();
});

}

function initializeLogement() {
  var logementsData = document.getElementById('map-log').getAttribute('data-logements');
  var logementInfo = JSON.parse(logementsData);

  console.log(logementInfo);

  var map = L.map('map-log').setView([logementInfo.latitude, logementInfo.longitude], 7);

  var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
  });

  map.addLayer(osmLayer);

  var customSvgIcon = L.divIcon({
    html: `<svg xmlns="http://www.w3.org/2000/svg" width="27.794" height="45.757" viewBox="0 0 47.794 65.757">
            <g id="Groupe_424" data-name="Groupe 424" transform="translate(1638.819 712.838)">
                <path class="marker-map" data-name="Soustraction 1" d="M23.4,63.807v0l-.9-1.269c-1.767-2.478-3.681-4.982-5.65-7.54l-.088-.116-.016-.02c-1.854-2.424-3.771-4.926-5.581-7.47l-.007-.015c-.176-.249-.395-.554-.645-.906l-.284-.4-.964-1.351-.006-.009C4.79,38.484,.177,32.046,.007,23.848L0,23.4A23.4,23.4,0,0,1,39.943,6.857,23.333,23.333,0,0,1,46.794,23.4l-.007.009v.438c-.169,8.2-4.788,14.641-9.255,20.873-.129.179-.256.358-.381.535-.109.152-.216.3-.321.451-.455.64-.888,1.25-1.186,1.673l-.007.015c-1.9,2.667-3.908,5.285-5.68,7.591l-.012.015-.258.334-.112.146c-1.767,2.306-3.595,4.692-5.273,7.06L23.4,63.8Zm0-54.253a13.855,13.855,0,1,0,9.788,4.055A13.755,13.755,0,0,0,23.4,9.553Z" transform="translate(-1638.319 -712.338)" fill="#6300FF" stroke="rgba(0,0,0,0)" stroke-miterlimit="10" stroke-width="1"></path>
            </g>
          </svg>`,
    className: "",
    iconSize: [24, 40],
    iconAnchor: [14, 42],
  });

  var marker = L.marker([logementInfo.latitude, logementInfo.longitude], { icon: customSvgIcon }).addTo(map);

  // Center the map on the marker
  map.setView(marker.getLatLng(), map.getZoom());
}

$('#debut, #fin').on('input', function() {
  var debut = $('#debut').val();
  var fin = $('#fin').val();

  // Vérifiez si l'un des champs de date est rempli
  if ((debut && !fin) || (!debut && fin)) {
      // Rendez les deux champs obligatoires
      console.log("required");
      $('#debut').prop('required', true);
      $('#fin').prop('required', true);
  } else {
      console.log("pas required");
      // Aucun des deux champs n'est rempli, retirez le required
      $('#debut').prop('required', false);
      $('#fin').prop('required', false);
  }
});



/* Formulaire AJAX */

$('.search_form').submit(function (e) {
    e.preventDefault();

    var nombre = $('#champNombre').val();
    var destination = $('#destination').val();
    var debut = $('#debut').val();
    var fin = $('#fin').val();
    var formAction = $(this).attr('action');

    $.ajax({
        type: 'POST',
        url: formAction,
        data: { 
          champNombre: nombre,
          destination: destination,
          debut: debut,
          fin: fin},
        success: function (data) {
          var listeLogementsElement = $(data).find('.liste-logements');
          $('.liste-logements').html(listeLogementsElement.html());
          var updatedData = $(data).find('#logements').attr('data-logements');

          $('.owl-carousel').owlCarousel({
            loop:true,
            margin:0,
            nav:false,
            mouseDrag: true,
            responsive:{
                0:{
                    items:1
                },
            }
          })
      
          // Update the data attribute of #logements
          $('#logements').attr('data-logements', updatedData);
      
          // Call initialize function to reload the map with updated data
          initialize();
      },
      
        error: function () {
            console.log('Erreur lors de la requête AJAX');
        }
    });
});


/* Date formulaire */

var startDateInput = $('#debut');
var endDateInput = $('#fin');

// Ajoutez un gestionnaire d'événements pour la date de début
var debutInput = $('#debut');
var finInput = $('#fin');

// Fonction pour mettre à jour la date minimale pour le champ de date de fin
function updateMinDate() {
  // Obtenez la date de début sélectionnée
  var debutValue = debutInput.val();

  // Vérifiez si la date de début est définie
  if (debutValue) {
    // Convertissez la date de début en objet Date
    var dateDebut = new Date(debutValue);

    // Ajoutez un jour à la date de début
    dateDebut.setDate(dateDebut.getDate() + 1);

    // Formattez la date pour l'attribut min
    var minDateFin = formatDate(dateDebut);

    // Mettez à jour la date minimale pour le champ de date de fin
    finInput.attr('min', minDateFin);
  }
}

// Attachez l'événement change au champ de date de début
debutInput.on('change', updateMinDate);

updateMinDate();


// Attachez l'événement change au champ de date de début
debutInput.on('change', updateMinDate);

/*finInput.on('change', function() {
      // Mettez à jour la date maximale pour la date de début en fonction de la date de fin sélectionnée
      debutInput.attr('max', finInput.val());
    });*/

// Fonction pour formater la date au format "YYYY-MM-DD"
function formatDate(date) {
  var year = date.getFullYear();
  var month = ('0' + (date.getMonth() + 1)).slice(-2);
  var day = ('0' + date.getDate()).slice(-2);
  return year + '-' + month + '-' + day;
}


$('.coeur').click(function (event) {
  // Empêcher la propagation de l'événement de clic vers les parents
  event.preventDefault();

  // Ajouter ici le code pour basculer la classe "rouge" sur la div .coeur
  $(this).toggleClass('rouge');
});

/*Carrousel logement */

$('.owl-carousel').owlCarousel({
    loop:false,
    margin:0,
    nav:true,
    mouseDrag: true,
    responsive:{
        0:{
            items:1
        },
    }
})

$('.owl-prev').click(function (event) {
    event.preventDefault();
});

$('.owl-next').click(function (event) {
    event.preventDefault();
});

/* Map */

function createMap() {
    var mapDiv = document.getElementById('map-log');
    if (mapDiv == null) {
        return;
    }

    var logementsData = mapDiv.getAttribute('data-logements');
    var logementInfo = JSON.parse(logementsData);

    console.log(logementInfo);

    var map = L.map('map-log').setView([logementInfo.latitude, logementInfo.longitude], 7);

    var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    });

    map.addLayer(osmLayer);

    var customSvgIcon = L.divIcon({
        html: `<svg xmlns="http://www.w3.org/2000/svg" width="27.794" height="45.757" viewBox="0 0 47.794 65.757">
            <g id="Groupe_424" data-name="Groupe 424" transform="translate(1638.819 712.838)">
                <path class="marker-map" data-name="Soustraction 1" d="M23.4,63.807v0l-.9-1.269c-1.767-2.478-3.681-4.982-5.65-7.54l-.088-.116-.016-.02c-1.854-2.424-3.771-4.926-5.581-7.47l-.007-.015c-.176-.249-.395-.554-.645-.906l-.284-.4-.964-1.351-.006-.009C4.79,38.484,.177,32.046,.007,23.848L0,23.4A23.4,23.4,0,0,1,39.943,6.857,23.333,23.333,0,0,1,46.794,23.4l-.007.009v.438c-.169,8.2-4.788,14.641-9.255,20.873-.129.179-.256.358-.381.535-.109.152-.216.3-.321.451-.455.64-.888,1.25-1.186,1.673l-.007.015c-1.9,2.667-3.908,5.285-5.68,7.591l-.012.015-.258.334-.112.146c-1.767,2.306-3.595,4.692-5.273,7.06L23.4,63.8Zm0-54.253a13.855,13.855,0,1,0,9.788,4.055A13.755,13.755,0,0,0,23.4,9.553Z" transform="translate(-1638.319 -712.338)" fill="#6300FF" stroke="rgba(0,0,0,0)" stroke-miterlimit="10" stroke-width="1"></path>
            </g>
          </svg>`,
        className: "",
        iconSize: [24, 40],
        iconAnchor: [14, 42],
    });

    var marker = L.marker([logementInfo.latitude, logementInfo.longitude], { icon: customSvgIcon }).addTo(map);

    // Center the map on the marker
    map.setView(marker.getLatLng(), map.getZoom());
}
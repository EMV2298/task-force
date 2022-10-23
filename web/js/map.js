
const map = document.querySelector('#map');
const lat = document.querySelector('#lat').value;
const long = document.querySelector('#long').value;

ymaps.ready(init);

function init(){
// Создание карты.
const myMap = new ymaps.Map(map, {
center: [long, lat],
zoom: 15
});

myMap.geoObjects.add(new ymaps.Placemark([long, lat], {
  preset: 'islands#icon',
  iconColor: '#4d38f5'
}));
}

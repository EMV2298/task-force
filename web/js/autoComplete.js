
const inputLat = document.querySelector('#addtask-lat');
const inputLong = document.querySelector('#addtask-long');

const autoCompleteJS = new autoComplete({
  selector: '#addtask-address',  
  debounce: 2000,
  searchEngine: 'loose',
  wrapper: false,
  data: {
    src: async (query) => {
        try {
            const source = await fetch(`/autocomplete/${query}`);
            let data = await source.json();            
            return data;
        } catch (error) {
            return error;
        }
        
    },
    cache:true,
      keys: ['address'],
    cache: false,
},
    resultsList: {
        tag: 'ul',
        class: 'autoComplete_location',
        element: (list, data) => {
            if (!data.results.length) {                
                const message = document.createElement("div");                
                message.setAttribute("class", "no_result");                
                message.textContent = `Адрес не найден`;                
                list.prepend(message);
            }
        },
        noResults: true,
    },
  resultItem: {
      highlight: true
  },
  events: {
      input: {
          selection: (event) => {
              const selection = event.detail.selection.value;
              autoCompleteJS.input.value = selection.address;
              inputLat.value = selection.lat;
              inputLong.value = selection.long;
          }
      }
  }
});

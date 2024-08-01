import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');



document.addEventListener("DOMContentLoaded", (event) => {



        let button = document.querySelector("#dropDown")

        button.addEventListener("click", (event) => {
                event.stopPropagation(); // Empêche la propagation de l'événement de clic au document
            button.classList.toggle('active')
            // alert('hello')
        })

    document.addEventListener('click',()=> {

        if (button.classList.contains('active')) {
            // Retire la classe 'active'
            button.classList.remove('active');
        }

    })

});

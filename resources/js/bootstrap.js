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

    function confirmSubmit() {
            // Afficher une alerte de confirmation
            return confirm('Are you sure you want to submit the form?');
    }


   document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
       button.addEventListener('click', (event) => {
           let modal = document.querySelector(event.target.getAttribute('data-bs-target'));
            modal.classList.toggle('show');
            modal.setAttribute('aria-hidden', 'false');

           modal.addEventListener('click', function (event) {

            if ( modal.classList.contains('show')) {
                modal.classList.toggle('show');
               }
                // if (event.target === modal && isModalOpen(modal)) {
                //     closeModal(modal, backdrop);
                // }
           });
           let modalChildren = modal.querySelectorAll('*');
            modalChildren.forEach(child => {
                child.addEventListener('click', function(event) {
                    event.stopPropagation(); // Empêche la propagation du clic
                });
            });
    });
});

});

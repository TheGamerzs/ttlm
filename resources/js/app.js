import './bootstrap';
import swal from 'sweetalert';


Livewire.on('alert', function(title, text, type = 'success') {
    swal({
        title: title,
        text: text,
        icon: type,
        padding: '2em'
    });
});

const gamePlanModal = new bootstrap.Modal(document.getElementById('gamePlan'), {});
Livewire.on('openGamePlan', function () {
    gamePlanModal.show();
});

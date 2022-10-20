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
Livewire.on('closeGamePlan', function () {
    gamePlanModal.hide();
});

const craftingGoalModal = new bootstrap.Modal(document.getElementById('craftingGoal'), {});
Livewire.on('openCraftingGoal', function () {
    craftingGoalModal.show();
});
Livewire.on('closeCraftingGoal', function () {
    craftingGoalModal.hide();
});

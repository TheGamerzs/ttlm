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

Livewire.on('ask', function(title, text, callback, ...data) {
    swal({
        title: title,
        text: text,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
        padding: '2em',
    }).then(function (success) {
        if (success) {
            Livewire.emit(callback, ...data)
        }
    })
});

const gamePlanModal = new bootstrap.Modal(document.getElementById('gamePlan'), {});
Livewire.on('openGamePlan', function () {
    gamePlanModal.show();
});


if (document.body.contains(document.getElementById('marketOrder'))) {
    const marketOrderModal = new bootstrap.Modal(document.getElementById('marketOrder'), {});
    Livewire.on('openMarketOrderModal', function () {
        marketOrderModal.show();
    });
    Livewire.on('closeMarketOrderModal', function () {
        marketOrderModal.hide();
    });
}

if (document.body.contains(document.getElementById('craftingGoal'))) {
    const craftingGoalModal = new bootstrap.Modal(document.getElementById('craftingGoal'), {});
    Livewire.on('openCraftingGoal', function () {
        craftingGoalModal.show();
    });
    Livewire.on('closeCraftingGoal', function () {
        craftingGoalModal.hide();
    });
}

if (document.body.contains(document.getElementById('MODetails'))) {
    const marketOrderDetailsModal = new bootstrap.Modal(document.getElementById('MODetails'), {});
    Livewire.on('showMarketOrderDetailsModal', function () {
        marketOrderDetailsModal.show();
    });
}


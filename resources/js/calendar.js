// Función global para cerrar el modal
function closeModal() {
    document.getElementById('eventoModal').classList.add('hidden'); // Oculta el modal
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    const modal = document.getElementById('eventoModal');
    const modalTitle = document.getElementById('modalTitle');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        dateClick: function(info) {
            modal.classList.remove('hidden'); // Muestra el modal
            modalTitle.textContent = 'Evento para el ' + info.dateStr; // Muestra la fecha en el título
        },
    });

    calendar.render();

    // Escuchar la tecla ESC para cerrar el modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
});

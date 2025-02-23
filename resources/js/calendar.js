// Función global para cerrar el modal
function closeModal() {
    document.getElementById("eventoModal").classList.add("hidden"); // Oculta el modal
    console.log(base_url);
}

// Función global para abrir el modal
function openModal() {
    document.getElementById("eventoModal").classList.remove("hidden"); // Muestra el modal
}

document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("closeModalBtn")
        .addEventListener("click", closeModal);

    var calendarEl = document.getElementById("calendar");
    const modal = document.getElementById("eventoModal");
    const modalTitle = document.getElementById("modalTitle");
    const formulario = document.querySelector("#form");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        locale: "es",

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth",
        },

        eventSources: {
            url: base_url + "/mostrar",
            method: "POST",
            extraParams: {
                _token: formulario._token.value,
            },
        },

        dateClick: function (info) {
            openModal();
            modalTitle.textContent = "Evento para el " + info.dateStr; // Muestra la fecha en el título

            formulario.reset(); // Limpia el formulario
            formulario.start.value = info.dateStr; // Asigna la fecha al campo start del formulario
            formulario.end.value = info.dateStr; // Asigna la fecha al campo end del formulario
        },

        eventClick: function (info) {
            axios
                .post(base_url + "/editar/" + info.event.id)
                .then((respuesta) => {
                    formulario.id.value = respuesta.data.id;
                    formulario.title.value = respuesta.data.title;
                    formulario.description.value = respuesta.data.description;
                    formulario.start.value = respuesta.data.start;
                    formulario.end.value = respuesta.data.end;

                    openModal();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    });

    // Escuchar la tecla ESC para cerrar el modal
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });

    document
        .getElementById("btnGuardar")
        .addEventListener("click", function () {
            submitData("/agregar");
        });

    document
        .getElementById("btnEliminar")
        .addEventListener("click", function () {
            submitData(
                "/borrar/" + formulario.id.value
            );
        });

    document
        .getElementById("btnModificar")
        .addEventListener("click", function () {
            submitData(
                "/actualizar/" +
                    formulario.id.value
            );
        });

    function submitData(url) {
        const datos_formulario = new FormData(formulario);
        const new_url = base_url + url;
        // formulario.reset();

        axios
            .post(new_url, datos_formulario)
            .then((respuesta) => {
                console.log("Todo correcto Padre!!");
                calendar.refetchEvents();
                closeModal();
            })
            .catch((error) => {
                console.log(error);
            });
    }

    calendar.render();
});

// Modal de creacion
function openCreateModal() {
    document.getElementById("btnModificar").style.display = "none";
    document.getElementById("btnEliminar").style.display = "none";
    document.getElementById("btnGuardar").style.display = "block";

    openModal();
}

// Modal de edición
function openEditModal() {
    document.getElementById("btnModificar").style.display = "block";
    document.getElementById("btnEliminar").style.display = "block";
    document.getElementById("btnGuardar").style.display = "none";

    openModal();
}

// Función para cerrar el modal
function closeModal() {
    document.getElementById("eventoModal").classList.add("hidden"); // Oculta el modal
    console.log(base_url);
}

// Función para abrir el modal
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
        displayEventTime: false,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,listWeek",
        },
        views: {
            listWeek: {
                duration: { weeks: 2 },
            },
            dayGridMonth: {
                titleFormat: {
                    year: "numeric",
                    month: "long",
                },
            },
        },

        // Cambiar el texto de los botones del headerToolbar
        buttonText: {
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Semana",
        },

        // Transformar los datos de los eventos para el formato que espera FullCalendar.
        eventDataTransform: function (eventData) {
            return {
                id: eventData.id,
                title: eventData.activity, // Mapea 'activity' a 'title'
                start: eventData.end_date, // Usa solo 'end_date' para la fecha del evento
                notes: eventData.notes, // 'notes' se mantiene igual
            };
        },

        eventSources: {
            url: base_url + "/mostrar",
            method: "GET",
            failure: function () {
                console.log("Error al cargar los eventos");
            },
        },

        dateClick: function (info) {
            modalTitle.textContent =
                "Actividad para el " +
                new Date(info.dateStr).toLocaleDateString("es-ES", {
                    weekday: "long",
                    month: "long",
                    day: "numeric",
                });

            formulario.reset(); // Limpia el formulario
            formulario.start_date.value = new Date().toISOString().slice(0, 10); // Fecha de inicio con la fecha actual
            formulario.end_date.value = info.dateStr; // Asigna la fecha donde hice click al campo end del formulario

            limpiarErrores();
            openCreateModal();
        },

        eventClick: function (info) {
            axios
                .post(base_url + "/editar/" + info.event.id)
                .then((respuesta) => {
                    modalTitle.textContent =
                        "Editar actividad - " +
                        new Date(info.event.start).toLocaleDateString("es-ES", {
                            weekday: "long",
                            month: "long",
                            day: "numeric",
                        });
                    formulario.id.value = respuesta.data.id;
                    formulario.activity.value = respuesta.data.activity;
                    formulario.subject.value = respuesta.data.subject;
                    formulario.start_date.value = respuesta.data.start_date;
                    formulario.end_date.value = respuesta.data.end_date;
                    formulario.notes.value = respuesta.data.notes;

                    limpiarErrores();
                    openEditModal();
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
            submitData("/borrar/" + formulario.id.value);
        });

    document
        .getElementById("btnModificar")
        .addEventListener("click", function () {
            if (formulario.id.value) {
                submitData("/actualizar/" + formulario.id.value);
            } else {
                console.log("ID no encontrado");
            }
        });

    function submitData(url) {
        const errors = validateForm();

        if (errors.length > 0) {
            mostrarErrores(errors);
            return; // No enviar el formulario si hay errores
        }

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

// Funcion para validar el formulario
function validateForm() {
    const activity = document.getElementById("activity").value;
    const subject = document.getElementById("subject").value;
    const start_date = document.getElementById("start_date").value;
    const end_date = document.getElementById("end_date").value;

    let errors = [];

    if (!activity) {
        errors.push("El campo actividad es obligatorio.");
    }

    if (!subject) {
        errors.push("El campo materia es obligatorio.");
    }

    if (!start_date) {
        errors.push("El campo fecha de inicio es obligatorio.");
    }

    if (!end_date) {
        errors.push("El campo fecha de fin es obligatorio.");
    }

    return errors;
}

// Funcion para mostrar los errores en el frontend
function mostrarErrores(errors) {
    limpiarErrores();
    const errorContainer = document.getElementById("errorContainer");

    // Mostrar los errores de cada campo
    errors.forEach((error) => {
        const errorElement = document.createElement("p");
        errorElement.textContent = error;
        errorContainer.appendChild(errorElement);
    });
}

// Funcion para limpiar los errores
function limpiarErrores() {
    document.getElementById("errorContainer").innerHTML = "";
}

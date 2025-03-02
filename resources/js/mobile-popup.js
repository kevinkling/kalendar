window.onload = function() {
    if (window.innerWidth <= 768) { // Detecta si el ancho de la pantalla es menor o igual a 768px
      var modal = document.getElementById("mobile-popup");
      var closeBtn = document.getElementsByClassName("close")[0];

      // Muestra el modal
      modal.style.display = "block";

      // Cierra el modal cuando el usuario hace clic en el botón de cierre
      closeBtn.onclick = function() {
        modal.style.display = "none";
      };

      // Cierra el modal si el usuario hace clic fuera de él
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };
    }
  };

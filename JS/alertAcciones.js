document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.getElementById('submitBtn');
    if (submitButton) {
        submitButton.addEventListener('click', function(event) {
            event.preventDefault(); // Evita que el formulari es submiti immediatament
            
            // Mostra el SweetAlert abans de fer l'enviament
            Swal.fire({
                title: "¿Vols guardar els canvis?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Guardar",
                denyButtonText: "No guardar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulari si es fa clic a "Guardar"
                    document.getElementById('editForm').submit();
                    Swal.fire("Guardat!", "", "success");
                } else if (result.isDenied) {
                    // No fer res si es fa clic a "No guardar"
                    Swal.fire("Els canvis no s'han guardat", "", "info");
                }
            });
        });
    }
});

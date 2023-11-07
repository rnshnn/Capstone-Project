document.getElementById("registerButton").addEventListener("click", function(event) {
    event.preventDefault();

    // Get references to the required fields
    const requiredFields = [
         "R_FULL", "R_FIRST", "R_MIDD", "R_COU", "R_YEAR", "R_CON", "R_ADD", "R_STRE", "R_BRGY", "R_MUNI", "R_CITY"
    ];

    let hasMissingFields = false;

    // Loop through the required fields and check if they are empty
    requiredFields.forEach(fieldName => {
        const field = document.getElementsByName(fieldName)[0];
        if (!field.value.trim()) {
            field.classList.add("required-field"); // Apply the required-field class
            hasMissingFields = true;
        } else {
            field.classList.remove("required-field"); // Remove the class if field has value
        }
    });

    if (!hasMissingFields) {
        // Check other conditions or actions here
        // Show the SweetAlert confirmation dialog
        Swal.fire({
            title: "Updating!",
            text: "Are you sure you want to update? If you click 'Confirm,' try to signing in again to update the form",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form
                document.querySelector(".custom-form").submit();
            }
        });
    } else {
        // Show an error message
        Swal.fire({
            title: "Required!",
            text: "Please fill in all required fields before submitting.",
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "OK"
        });
    }
});
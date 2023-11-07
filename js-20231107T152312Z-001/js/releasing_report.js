
let canGenerate = true; // Flag to control whether report generation is allowed

document.getElementById('btn_release').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    if (canGenerate) {
        Swal.fire({
            title: 'Generate Report',
            text: 'Are you sure you want to generate the report this requests?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, generate it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('btn_clicked').value = '1';
                document.getElementById('form_release').submit();
                canGenerate = false;
                setTimeout(() => {
                    canGenerate = true;
                }, 7 * 24 * 60 * 60 * 1000); // Allow generating again after 7 days
            }
        });
    } else {
        Swal.fire({
            title: 'Generate Report',
            text: 'Please wait for 7 days before generating again.',
            icon: 'info',
        });
    }
});




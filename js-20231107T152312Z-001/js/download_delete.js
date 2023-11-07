let Generate = true; // Flag to control whether report generation is allowed

    document.getElementById('btn_decline').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        if (Generate) {
            Swal.fire({
                title: 'Download Form',
                text: 'Are you sure you want to download and Delete all requests?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, download it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set a flag to trigger the download on form submission
                    document.querySelector('input[name="click"]').value = '2';
                    document.getElementById('form_decline').submit();
                    Generate = false;
                    setTimeout(() => {
                        Generate = true;
                    }, 7 * 24 * 60 * 60 * 1000); // Allow generating again after 7 days
                    
                    // Reload the page after submission
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // Reload after 1 second (adjust as needed)
                }
            });
        } else {
            Swal.fire({
                title: 'Download',
                text: 'Please wait for 7 days before Download.',
                icon: 'info',
            });
        }
    });
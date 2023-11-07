<script>
    document.getElementById('process_assign').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const selectedO_ASIG = document.querySelector('select[name="O_ASIG"]').value;

        if (selectedO_ASIG === 'Select') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an assign name!',
            });
        } else {
            Swal.fire({
                title: 'Confirm Assignment',
                text: `Are you sure you want to assign On-process requests from "${selectedO_ASIG}" to others?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, assign it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form here
                    document.getElementById('form_process').submit();
                }
            });
        }
    });
</script>

    <script>
    let canGenerate = true; // Flag to control whether report generation is allowed

    document.getElementById('btn_process').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        if (canGenerate) {
            Swal.fire({
                title: 'Generate Report',
                text: 'Are you sure you want to generate the report for processing requests?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('btn_clicked').value = '1';
                    document.getElementById('form_processing').submit();
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
    </script>
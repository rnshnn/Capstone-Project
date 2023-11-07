<script>
    document.getElementById('btn_assign').addEventListener('click', function() {
        const selectedS_ASIG = document.querySelector('select[name="S_ASIG"]').value;

        if (selectedS_ASIG === 'Select') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select an assign name!',
            });
        } else {
            Swal.fire({
                title: 'Confirm Assignment',
                text: `Are you sure you want to assign pending requests from "${selectedS_ASIG}" to others?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, assign it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form here
                    document.getElementById('form_assign').submit();
                }
            });
        }
    });
    </script>

<script>
    let canGenerate = true; // Flag to control whether report generation is allowed

    document.getElementById('btn_pending').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        if (canGenerate) {
            Swal.fire({
                title: 'Generate Report',
                text: 'Are you sure you want to generate the report in pending request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('btn_clicked').value = '1';
                    document.getElementById('form_report').submit();
                    canGenerate = false;
                   // setTimeout(() => {
                     //   canGenerate = true;
                   // }, 5000); 
                   setTimeout(() => {
                        canGenerate = true;
                    }, 7 * 24 * 60 * 60 * 1000); 
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
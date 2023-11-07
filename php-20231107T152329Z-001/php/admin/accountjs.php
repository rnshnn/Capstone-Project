<script>
    $(document).ready(function() {
        var accountCount = 0;

        $("#addField").click(function() {
            accountCount++;
            var newRow = '<div class="form-row add_one">' +
                '<div class="form-group col-md-2">' +
                '    <input type="text" class="form-control" name="accounts[' + accountCount +
                '][R_FULL]" placeholder="Enter name">' +
                '</div>' +
                '<div class="form-group col-md-2">' +
                '    <input type="text" class="form-control" name="accounts[' + accountCount +
                '][R_STU]" placeholder="Enter school ID">' +
                '</div>' +
                '<div class="form-group col-md-2">' +
                '    <input type="email" class="form-control" name="accounts[' + accountCount +
                '][R_EMAIL]" placeholder="Enter email">' +
                '</div>' +
                '<div class="form-group col-md-2">' +
                '    <input type="text" class="form-control" name="accounts[' + accountCount +
                '][R_COM]" placeholder="Enter company">' +
                '</div>' +
                '<div class="form-group col-md-2" style="display:none">' +
                '    <input type="text" class="form-control" name="accounts[' + accountCount +
                '][R_POS]" placeholder="Enter position" id="R_POS">' +
                '</div>' +
                '<div class="form-group col-md-2">' +
                '    <input type="password" class="form-control" name="accounts[' + accountCount +
                '][R_PASS]" placeholder="Enter password">' +
                '</div>' +
                '<div class="form-group col-md-2">' +
                '    <select class="form-control" name="accounts[' + accountCount +
                '][R_VERIFIED]" id="R_VERIFIED">' +
                '        <option hidden></option>' +
                '        <option value="3">Registrar</option>' +
                '        <option value="4">Admin</option>' +
                '    </select>' +
                '</div>' +
                '<div class="form-group col-md-2" style="display:none">' +
                '    <select class="form-control" name="accounts[' + accountCount +
                '][R_RORA]" id="R_RORA">' +
                '        <option value="approve">1</option>' +
                '    </select>' +
                '</div>' +
                '<div class="form-group col-md-2" style="display:none">' +
                '    <input type="text" class="form-control" name="accounts[' + accountCount +
                '][R_SMS]" value="Enabled">' +
                '</div>' +
                '</div>';

            $(".add_one:last").after(newRow);
        });

        // Update R_POS based on R_VERIFIED selection
        $(document).on('change', '[name^="accounts"][name$="[R_VERIFIED]"]', function() {
            var selectedValue = $(this).val();
            var positionInput = $(this).closest('.form-row').find(
                '[name^="accounts"][name$="[R_POS]"]');

            if (selectedValue === '3') {
                // If Registrar is selected, update R_POS to Registrar
                positionInput.val('Registrar');
            } else if (selectedValue === '4') {
                // If Admin is selected, update R_POS to Admin
                positionInput.val('Admin');
            }
        });

        $("#registerButton").click(function(event) {
            event.preventDefault();

            // Get references to the required fields
            const requiredFields = [
                "accounts[0][R_FULL]", "accounts[0][R_STU]", "accounts[0][R_EMAIL]",
                "accounts[0][R_COM]", "accounts[0][R_PASS]", "accounts[0][R_VERIFIED]",
                "accounts[" + accountCount + "][R_FULL]", "accounts[" + accountCount + "][R_STU]",
                "accounts[" + accountCount + "][R_EMAIL]", "accounts[" + accountCount + "][R_COM]",
                "accounts[" + accountCount + "][R_PASS]", "accounts[" + accountCount +
                "][R_VERIFIED]"
            ];

            let hasMissingFields = false;

            // Loop through the required fields and check if they are empty
            requiredFields.forEach(fieldName => {
                const field = document.getElementsByName(fieldName)[0];
                if (!field.value.trim()) {
                    field.classList.add("required-field"); // Apply the required-field class
                    hasMissingFields = true;
                } else {
                    field.classList.remove(
                        "required-field"); // Remove the class if the field has a value
                }
            });

            // Check if there are any hidden R_POS fields with missing values
            $('[name^="accounts"][name$="[R_POS]"]').each(function() {
                if ($(this).val() === '') {
                    $(this).addClass("required-field");
                    hasMissingFields = true;
                } else {
                    $(this).removeClass("required-field");
                }
            });

            if (!hasMissingFields) {
                // Check other conditions or actions here
                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: "Updating!",
                    text: "Are you sure you want to add this account?",
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
    });
    </script>

    <script>
    const userPosElements = document.querySelectorAll('.user-pos');


    userPosElements.forEach(element => {
        const pos = element.textContent.trim();
        element.style.color = (pos === 'Admin') ? 'orange' : ((pos === 'Registrar') ? 'darkblue' : (pos ===
            'Disable') ? 'red' : (pos === 'Enable') ? 'darkgreen' : (pos === 'Active') ? 'green' : (
            pos === 'Not Active') ? 'grey' : 'black');
    });
    </script>


    <script>
    function confirmDisable(i) {
        Swal.fire({
            title: 'Confirm Disable',
            text: 'Are you sure you want to disable this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, disable it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`disabled_id${i}`).submit();
            }
        });
    }

    function confirmEnable(i) {
        Swal.fire({
            title: 'Confirm Enable',
            text: 'Are you sure you want to enable this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, enable it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`enabled_id${i}`).submit();
            }
        });
    }
    </script>

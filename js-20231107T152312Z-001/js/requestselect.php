<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get references to the relevant form elements
    const documentType = document.getElementById("documentType");
    const numCopies = document.getElementById("numCopies");
    const documentType2 = document.getElementById("documentType_2");
    const numCopies2 = document.getElementById("numCopies_2");
    const documentType3 = document.getElementById("documentType_3");
    const numCopies3 = document.getElementById("numCopies_3");
    const total1 = document.getElementById("total_1");
    const total2 = document.getElementById("total_2");
    const total3 = document.getElementById("total_3");
    const price = document.getElementById("price");
    const firstRequest = document.getElementById("firstRequest");
    const S_DEL = document.getElementById("S_DEL");

    // Function to get the price based on the selected document type
    function getPrice(documentTypeValue) {
        switch (documentTypeValue) {
            case "TOR for reference":
            case "TOR for board exam":
            case "TOR for copy school":
                return 400;
            case "Certificate of Unit Earned":
            case "Subject Description":
            case "Certificate of Enrollment":
            case "Certificate of Graduation":
            case "Certificate of Grades":
            case "Certificate of Enrolled Semester":
            case "Certificate of English Proficiency":
            case "Certificate of NSTP Serial Number":
            case "Certificate of Honor":
                return 150;
            case "diploma":
                return 800;
            case "FORM - 137":
            case "HONOROBALE DISMISSAL":
            case "CAV":
            case "Authentication":
                return 200;
            default:
                return 0;
        }
    }

    // Function to calculate the delivery charges based on location
    function calculateDeliveryCharges(location) {
        const deliveryCharges = {
            Aliaga: 100,
            Bongabon: 100,
            'Cabanatuan City': 160,
            Cabiao: 100,
            Carranglan: 100,
            Cuyapo: 100,
            'Gabaldon': 100,
            'Gapan City': 30,
            'General Mamerto Natividad': 150,
            'General Tinio': 100,
            Guimba: 100,
            Jaen: 120,
            Laur: 160,
            Licab: 190,
            Llanera: 100,
            Lupao: 200,
            Nampicuan: 100,
            'Palayan City': 130,
            Pantabangan: 100,
            Peñaranda: 100,
            Quezon: 130,
            Rizal: 100,
            'San Antonio': 100,
            'San Isidro': 100,
            'San Jose City': 120,
            'San Leonardo': 100,
            'Santa Rosa': 100,
            'Santo Domingo': 100,
            Talavera: 180,
            Talugtug: 160,
            Zaragoza: 150
        };

        return deliveryCharges[location] || 0;
    }

    // Function to calculate the total price
    function calculatePrice() {
        const price1 = getPrice(documentType.value);
        const price2 = getPrice(documentType2.value);
        const price3 = getPrice(documentType3.value);

        // Calculate the totals for each request
        const totalPrice1 = numCopies.value * price1;
        const totalPrice2 = numCopies2.value * price2;
        const totalPrice3 = numCopies3.value * price3;

        // Check if the first request is "yes" and set the price accordingly
        if (firstRequest.value === "yes") {
            var firstRequestPrice = 0;
            numCopies.value = 1; // Set numCopies to 1 copy
            numCopies.readOnly = true;
            addRequestButton.disabled = true;
        } else {
            var firstRequestPrice = totalPrice1;
            numCopies.readOnly = false;
            addRequestButton.disabled = false;
        }

        // Calculate delivery charges if "Deliver" is chosen in S_DEL
        const deliveryCharges = S_DEL.value === "deliver" ? calculateDeliveryCharges(<?php echo json_encode($_SESSION['R_MUNI']); ?>) : 0;

        // Calculate the overall price
        const totalPrice = firstRequestPrice + totalPrice2 + totalPrice3 + deliveryCharges;

        // Update the total fields
        total1.value = totalPrice1;
        total2.value = totalPrice2;
        total3.value = totalPrice3;

        // Update the price field
        price.value = `₱${totalPrice}`;
    }

    // Event listeners to trigger price calculation on change
    documentType.addEventListener("change", calculatePrice);
    numCopies.addEventListener("change", calculatePrice);
    documentType2.addEventListener("change", calculatePrice);
    numCopies2.addEventListener("change", calculatePrice);
    documentType3.addEventListener("change", calculatePrice);
    numCopies3.addEventListener("change", calculatePrice);
    firstRequest.addEventListener("change", calculatePrice);
    S_DEL.addEventListener("change", calculatePrice);

    // Initial calculation
    calculatePrice();
});

</script>


<script>
const documentTypeSelect = document.getElementById("documentType");
const documentTypeSelect2 = document.getElementById("documentType_2");
const documentTypeSelect3 = document.getElementById("documentType_3");
const numCopiesContainer = document.getElementById("numCopiesContainer");
const firstRequestContainer = document.getElementById("firstRequestContainer");
const deliver = document.getElementById("deliver");
const fileInputContainer = document.getElementById("fileInputContainer");
const priceContainer = document.getElementById("priceContainer");
const priceInput = document.getElementById("price");
const numCopiesSelect = document.getElementById("numCopies");
const numCopiesSelect2 = document.getElementById("numCopies_2");
const numCopiesSelect3 = document.getElementById("numCopies_3");
const firstRequestSelect = document.getElementById("firstRequest");
const addRequestButton = document.getElementById("add_request");
const hide_1_2 = document.getElementById("hide");


addRequestButton.addEventListener("click", function() {
    // Hide first request and set its value to 'No'
    firstRequestSelect.value = "no";
    firstRequestContainer.style.display = "none";
    fileInputContainer.style.display = "block";

    // Set firstRequestSelect, documentTypeSelect, and numCopiesSelect to readonly
    firstRequestSelect.readOnly = true;
    documentTypeSelect.readOnly = true;

    // Enable second request form elements
    documentTypeSelect2.readOnly = false;
    numCopiesSelect2.readOnly = false;
    firstRequestSelect2.readOnly = false;

    // Hide or show second request container as needed
    if (selectedOption === "Select") {
        secondRequestContainer.style.display = "none";
    } else {
        secondRequestContainer.style.display = "block";
    }
});

// Add an event listener to documentTypeSelect2 to toggle firstRequestSelect2
documentTypeSelect2.addEventListener("change", function() {
    const selectedOption = this.value;

    if (selectedOption === "Select") {
        firstRequestSelect2.readOnly = true;
    } else {
        firstRequestSelect2.readOnly = false;
    }
});

documentTypeSelect2.addEventListener("change", function() {
    const selectedOption = this.value;

    if (selectedOption) {

        // Show the container based on selected document type
        if (selectedOption === "TOR for board exam") {
            torExamContainer.style.display = "block";
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "FORM - 137") {
            form137Container.style.display = "block";
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "TOR for copy school") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
            torCopyContainer.style.display = "block";
        } else if (selectedOption === "HONOROBALE DISMISSAL") {
            dismissalContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "CAV") {
            cavContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "Authentication") {
            authContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "diploma") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "TOR for reference") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        }
        
        

        // Define the document-specific information
        const documentInfo = getDocumentInfo(selectedOption);

        // Set the document-specific labels and conditions
        setDocumentLabels(selectedOption, documentInfo);
    } else {
        
    }
});

documentTypeSelect3.addEventListener("change", function() {
    const selectedOption = this.value;

    if (selectedOption) {

        // Show the container based on selected document type
        if (selectedOption === "TOR for board exam") {
            torExamContainer.style.display = "block";
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "FORM - 137") {
            form137Container.style.display = "block";
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "TOR for copy school") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
            torCopyContainer.style.display = "block";
        } else if (selectedOption === "HONOROBALE DISMISSAL") {
            dismissalContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "CAV") {
            cavContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "Authentication") {
            authContainer.style.display = "block";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "diploma") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        } else if (selectedOption === "TOR for reference") {
            firstRequestContainer.style.display = "none";
            firstRequestSelect.style.display = "none";
        }
        
        // Define the document-specific information
        const documentInfo = getDocumentInfo(selectedOption);

        // Set the document-specific labels and conditions
        setDocumentLabels(selectedOption, documentInfo);


    } else {

    }
});


documentTypeSelect.addEventListener("change", function() {
    const selectedOption = this.value;

    if (selectedOption) {
        deliver.style.display = "block";
        numCopiesContainer.style.display = "block";
        firstRequestContainer.style.display = "block";
        priceContainer.style.display = "block";
        add_request.style.display = "inline-block";


        torCopyContainer.style.display = "none";
        torExamContainer.style.display = "none";
        form137Container.style.display = "none";
        dismissalContainer.style.display = "none";
        cavContainer.style.display = "none";
        authContainer.style.display = "none";

        // Show the container based on selected document type
        if (selectedOption === "TOR for board exam") {
            torExamContainer.style.display = "block";
        } else if (selectedOption === "FORM - 137") {
            form137Container.style.display = "block";
        } else if (selectedOption === "TOR for copy school") {
            torCopyContainer.style.display = "block";
        } else if (selectedOption === "HONOROBALE DISMISSAL") {
            dismissalContainer.style.display = "block";
        } else if (selectedOption === "CAV") {
            cavContainer.style.display = "block";
        } else if (selectedOption === "Authentication") {
            authContainer.style.display = "block";
        }

        // Define the document-specific information
        const documentInfo = getDocumentInfo(selectedOption);

        // Set the document-specific labels and conditions
        setDocumentLabels(selectedOption, documentInfo);

        // Calculate price based on selected document type, copies, and first request

    } else {}
});

firstRequestSelect.addEventListener("change", function() {
    const isFirstRequest = this.value === "yes";

    if (isFirstRequest) {


        addRequestButton.style.display = "none";
        hide_1_2.style.display = "none";
        documentTypeSelect2.style.display = "none";
        numCopiesSelect2.style.display = "none";
        documentTypeSelect3.style.display = "none";
        numCopiesSelect3.style.display = "none";
    } else {
        // Enable the 2nd and 3rd request sections
        documentTypeSelect2.style.display = "block";
        numCopiesSelect2.style.display = "block";
        documentTypeSelect3.style.display = "block";
        numCopiesSelect3.style.display = "block";
        hide_1_2.style.display = "block";

    }

});

firstRequestSelect.addEventListener("change", function() {
    const isFirstRequest = this.value === "no";

    if (isFirstRequest) {

        addRequestButton.style.display = "inline-block";

    } else {

    }

});

function getDocumentInfo(selectedOption) {
    // Define the document-specific information
    const documentInfo = {
        "TOR for board exam": {
            label: "TOR For Board Exam",
            isFirstRequest: true
        },
        "TOR for copy school": {
            label: "TOR Copy for School",
            isFirstRequest: true
        },
        "TOR for reference": {
            label: "TOR for reference",
            isFirstRequest: true
        },
        "FORM - 137": {
            label: "Form 137 For Reference",

            isFirstRequest: true
        },
        "HONOROBALE DISMISSAL": {
            label: "HONOROBALE DISMISSAL (HD)",

            isFirstRequest: true
        },
        "CAV": {
            label: "CERTIFICATION, AUTHENTICATION, AND VERIFICATION (CAV)",

            isFirstRequest: true
        },
        "Authentication": {
            label: "AUTHENTICATION",
            isFirstRequest: true
        },
        "Certificate of Unit Earned": {
            label: "Certificate of Unit Earned",
            isFirstRequest: true
        },
        "Certificate of Enrollment": {
            label: "Certificate of Enrollment",
            isFirstRequest: true
        },
        "Certificate of Graduation": {
            label: "Certificate of Graduation",
            isFirstRequest: true
        },
        "Certificate of Grades": {
            label: "Certificate of Grades",
            isFirstRequest: true
        },
        "Certificate of Enrolled Semester": {
            label: "Certificate of Enrolled Semester",
            isFirstRequest: true
        },
        "Certificate of English Proficiency": {
            label: "Certificate of English Proficiency",
            isFirstRequest: true
        },
        "Certificate of NSTP Serial Number": {
            label: "Certificate of NSTP Serial Number",
            isFirstRequest: true
        },
        "Certificate of Honor": {
            label: "Certificate of Honor",
            isFirstRequest: true
        },
        "Subject Description": {
            label: "Subject Description",
            isFirstRequest: true
        },
        "diploma": {
            label: "diploma",
            isFirstRequest: true
        },
        "hatdog": {
            label: "hatdog",
            isFirstRequest: false
        }
    };

    return documentInfo[selectedOption];
}

function setDocumentLabels(selectedOption, documentInfo) {
    // Set the document-specific labels and conditions
    const isFirstRequest = documentInfo.isFirstRequest;


    if (isFirstRequest && ["FORM - 137", "TOR for board exam", "TOR for copy school", "HONOROBALE DISMISSAL", "CAV",
            "Authentication", "TOR for reference", "Certificate of Unit Earned", "Certificate of Enrollment",
            "Certificate of Graduation", "Certificate of Grades", "Certificate of Enrolled Semester", "Certificate of English Proficiency", "Certificate of NSTP Serial Number",
            "Certificate of Honor", "Subject Description", "diploma"
        ].includes(selectedOption)) {
        fileInputContainer.style.display = "block";
    } else {
        fileInputContainer.style.display = "none";

    }
    if (["FORM - 137", "TOR for board exam", "TOR for copy school", "diploma", "TOR for reference"].includes(
            selectedOption)) {
        firstRequestContainer.style.display = "block";
    } else {
        firstRequestContainer.style.display = "none";
        firstRequest.value = "no"; // Set the value to 'no'
        hide_1_2.style.display = "block";
        documentTypeSelect2.style.display = "block";
        numCopiesSelect2.style.display = "block";
        documentTypeSelect3.style.display = "block";
        numCopiesSelect3.style.display = "block";

    }


}
</script>


<script>
let numRequests = 1; // Initialize with one request

document.getElementById("add_request").addEventListener("click", function() {
    numRequests++; // Increment the request counter

    // Show the second request section when adding the second request
    if (numRequests === 2) {
        document.getElementById("secondRequestContainer").style.display = "block";
        document.getElementById("numCopiesContainer_2").style.display = "block";

        // Make first request fields read-only
        document.getElementById("documentType").readOnly = true;
        document.getElementById("numCopies").readOnly = true;
        document.getElementById("firstRequest").readOnly = true;
    }

    // Show the third request section when adding the third request
    if (numRequests === 3) {
        document.getElementById("thirdRequestContainer").style.display = "block";
        document.getElementById("numCopiesContainer_3").style.display = "block";

        // Make second request fields read-only
        document.getElementById("documentType_2").readOnly = true;
        document.getElementById("numCopies_2").readOnly = true;
        document.getElementById("firstRequest_2").readOnly = true;

        // Hide the "Add Request" button after adding the third request
        this.style.display = "none";
    }
});
</script>

<script>
document.getElementById("confirmRegistrations").addEventListener("click", function() {
    const selectedOption = document.getElementById("documentType").value;
    const selectedOption2 = document.getElementById("documentType_2").value;
    const selectedOption3 = document.getElementById("documentType_3").value;

    // Check if documentType for the first request is selected
    if (selectedOption === "Select") {
        Swal.fire({
            title: "Error",
            confirmButtonColor: "#3085d6",
            text: "Please select a document request type for the first request.",
            icon: "error",
        });
        return; // Stop form submission
    }

    // Check if documentType for the second request is selected (if applicable)
    // Check if documentType for the second request is selected (if applicable)
    if (document.getElementById("secondRequestContainer").style.display === "block") {
        const selectedOption2 = document.getElementById("documentType_2").value;
        if (selectedOption2 === "Select" || selectedOption2 === "") {
            Swal.fire({
                title: "Error",
                confirmButtonColor: "#3085d6",
                text: "Please select a document request type for the second request.",
                icon: "error",
            });
            return; // Stop form submission
        }
    }

    // Check if documentType for the third request is selected (if applicable)
    if (document.getElementById("thirdRequestContainer").style.display === "block") {
        const selectedOption3 = document.getElementById("documentType_3").value;
        if (selectedOption3 === "Select" || selectedOption3 === "") {
            Swal.fire({
                title: "Error",
                confirmButtonColor: "#3085d6",
                text: "Please select a document request type for the third request.",
                icon: "error",
            });
            return; // Stop form submission
        }
    }


    if (selectedOption === 'TOR for board exam' || selectedOption === 'TOR for copy school' ||
        selectedOption === 'FORM - 137' || selectedOption === 'HONOROBALE DISMISSAL' ||
        selectedOption === 'CAV' || selectedOption === 'Authentication') {

        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files.length) {
            Swal.fire({
                title: 'Upload Required',
                confirmButtonColor: "#3085d6",
                text: 'Please upload the required file for this document type.',
                icon: 'error',
            });
            return; // Stop form submission
        }
    }

    if (
        selectedOption2 === 'TOR for board exam' || selectedOption2 === 'TOR for copy school' ||
        selectedOption2 === 'FORM - 137' || selectedOption2 === 'HONOROBALE DISMISSAL' ||
        selectedOption2 === 'CAV' || selectedOption2 === 'Authentication'
    ) {
        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files.length) {
            Swal.fire({
                title: 'Upload Required',
                confirmButtonColor: "#3085d6",
                text: 'Please upload the required file for the second document type.',
                icon: 'error',
            });
            return; // Stop form submission
        }
    }

    // Check for file upload for the third document type (if applicable)
    if (
        selectedOption3 === 'TOR for board exam' || selectedOption3 === 'TOR for copy school' ||
        selectedOption3 === 'FORM - 137' || selectedOption3 === 'HONOROBALE DISMISSAL' ||
        selectedOption3 === 'CAV' || selectedOption3 === 'Authentication'
    ) {
        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files.length) {
            Swal.fire({
                title: 'Upload Required',
                confirmButtonColor: "#3085d6",
                text: 'Please upload the required file for the third document type.',
                icon: 'error',
            });
            return; // Stop form submission
        }
    }



    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to submit your request.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('requestForm').submit(); // Submit the form
        }
    });
});
</script>
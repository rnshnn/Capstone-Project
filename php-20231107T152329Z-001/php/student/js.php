<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="../js/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
// Get all the anchor tags with the list-group-item-action class
const listItems = document.querySelectorAll('.list-group-item-action');

// Loop through each anchor tag
listItems.forEach(item => {
    // Add a click event listener to each anchor tag
    item.addEventListener('click', () => {
        // Remove the 'active' class from all anchor tags
        listItems.forEach(listItem => {
            listItem.classList.remove('active');
        });

        // Add the 'active' class to the clicked anchor tag
        item.classList.add('active');
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lastNameInput = document.getElementById('R_FULL');
    const generatedProfile = document.getElementById('generated-profile');

    function updateProfilePicture() {
        const lastName = lastNameInput.value.trim();

        if (lastName) {
            const initials = (lastName.charAt(0)).toUpperCase();

            // Create an image element with the initials as content
            const img = document.createElement('img');
            img.src = 'data:image/svg+xml,' + encodeURIComponent(getInitialsSVG(initials));
            img.alt = 'Profile Picture';

            // Clear the previous profile picture
            generatedProfile.innerHTML = '';

            // Append the profile picture to the generated-profile div
            generatedProfile.appendChild(img);
        } else {
            // Clear the profile picture if either name field is empty
            generatedProfile.innerHTML = '';
        }
    }

    // Call the function on input change
    lastNameInput.addEventListener('input', updateProfilePicture);

    // Function to generate an SVG with initials
    function getInitialsSVG(initials) {
        return `
            <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" rx="50%" ry="50%" fill="#333"/>
                <text x="50%" y="50%" text-anchor="middle" dy="0.35em" fill="#ffffff" font-size="60">
                    ${initials}
                </text>
            </svg>
        `;
    }

    // Initial update
    updateProfilePicture();
});
</script>

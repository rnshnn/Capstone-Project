<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="../js/sweetalert2.all.min.js"></script>


<script>

//const listItems = document.querySelectorAll('.list-group-item-action');


//listItems.forEach(item => {

   // item.addEventListener('click', () => {

      //  listItems.forEach(listItem => {
    //        listItem.classList.remove('active');
    //    });


    //    item.classList.add('active');
   // });
//});
</script>



<script>
// Get the sidebar and toggle button elements
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');

// Function to toggle the sidebar
function toggleSidebar() {
    if (sidebar.style.display === 'none' || sidebar.style.display === '') {
        // Show the sidebar
        sidebar.style.display = 'block';
    } else {
        // Hide the sidebar
        sidebar.style.display = 'none';
    }
}

// Function to check screen width and toggle sidebar and button
function checkScreenWidth() {
    if (window.innerWidth <= 1200) {
        // Hide the sidebar and show the button
        sidebar.style.display = 'none';
        sidebarToggle.style.display = 'block';
    } else {
        // Show both the sidebar and the button
        sidebar.style.display = 'block';
        sidebarToggle.style.display = 'none';
    }
}

// Add a click event listener to the toggle button
sidebarToggle.addEventListener('click', toggleSidebar);

// Add a resize event listener to handle screen size changes
window.addEventListener('resize', checkScreenWidth);

// Initially check screen width and toggle sidebar and button
checkScreenWidth();
</script>







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


  
document.addEventListener('DOMContentLoaded', function() {
    // Get account icon element
    var accountIcon = document.getElementById('account-icon');

    // Get account box element
    var accountBox = document.querySelector('.account-box');

    // Function to toggle account box visibility
    function toggleAccountBox() {
        if (accountBox.style.display === 'none' || accountBox.style.display === '') {
            accountBox.style.display = 'block';
            // Add event listener to hide account box when anywhere outside is clicked
            document.addEventListener('click', hideAccountBoxOutside);
        } else {
            accountBox.style.display = 'none';
            // Remove event listener to hide account box when anywhere outside is clicked
            document.removeEventListener('click', hideAccountBoxOutside);
        }
    }

    // Function to hide account box when anywhere outside is clicked
    function hideAccountBoxOutside(event) {
        if (!accountIcon.contains(event.target) && !accountBox.contains(event.target)) {
            accountBox.style.display = 'none';
            // Remove event listener to hide account box when anywhere outside is clicked
            document.removeEventListener('click', hideAccountBoxOutside);
        }
    }

    // Add event listener to account icon
    accountIcon.addEventListener('click', toggleAccountBox);
});

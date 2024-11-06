document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdowns
    var dropdowns = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    dropdowns.map(function (dropdownToggle) {
        return new bootstrap.Dropdown(dropdownToggle)
    });

    // Add click event listeners to language items
    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.getAttribute('href');
        });
    });
});

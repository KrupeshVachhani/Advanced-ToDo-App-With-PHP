// script.js
$(document).ready(function () {
    $('#toggle-sidebar').on('click', function () {
        var sidebar = $('#sidebar');
        var left = sidebar.css('left') === '-250px' ? '0px' : '-250px';
        sidebar.css('left', left);
    });
});

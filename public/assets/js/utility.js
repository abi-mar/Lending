// stores common utility functions for the application
$(document).ready(function() {
    $('.decimal').on('input', function() {
                this.value = this.value
                    .replace(/[^\d.]/g, '')             // numbers and decimals only
                    //.replace(/(^[\d]{4})[\d]/g, '$1')   // not more than 4 digits at the beginning
                    .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
                    .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
            });

    // Format with commas as user types
    $('.comma_amount').on('input', function() {
        let value = this.value.replace(/,/g, '').replace(/[^\d.]/g, '');
        let parts = value.split('.');
        // Format integer part with commas
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        // Limit to 2 decimal places
        if (parts[1]) parts[1] = parts[1].substring(0,2);
        this.value = parts.join('.');
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });

    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    
});
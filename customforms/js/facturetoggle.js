// Load this script in the custom form you want to use it in.
// This script is used to toggle the fields in the form.

// Get all the fields in the form with the class facture
const all_facture_fields = $('.facture');

// Get the toggle switch
const toggleSwitch = $('#toggleFields');

// Disable all the fields if the toggle switch is checked on load of the page
if (toggleSwitch.is(':checked')) {
    all_facture_fields.prop('disabled', true);
}

// Listen for the change event on the toggle switch
toggleSwitch.change(function (e) {
    let isChecked = $(this).is(':checked');
    all_facture_fields.prop('disabled', isChecked);
})

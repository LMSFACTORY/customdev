// Enable es6
/* jshint esversion: 6 */


document.addEventListener('DOMContentLoaded', () => {
    // Select all checkboxes with the "enableDateCheckbox" class
    const checkboxes = document.querySelectorAll('.enableDateCheckbox');
    const sessionConcern = document.querySelector('#session_concern'); // The one-and-only field

    // Helper function: enable sessionConcern if at least one checkbox is checked
    // checked checkbox INSIDE a .domain_intervention container
    function updateSessionConcern() {
        // Get all checkboxes inside .domain_intervention containers
        const domainInterventionCheckboxes = document.querySelectorAll('.domain_intervention #audit_pedagogic.enableDateCheckbox');

        // Check if ANY are checked
        const anyCheckedInDomainIntervention = Array
            .from(domainInterventionCheckboxes)
            .some(checkbox => checkbox.checked);

        if (anyCheckedInDomainIntervention) {
            sessionConcern.removeAttribute('disabled');
        } else {
            sessionConcern.setAttribute('disabled', true);
            sessionConcern.value = ''; // Clear the value if no checkboxes are checked
            sessionConcern.removeAttribute('value');
        }
    }

    checkboxes.forEach((checkbox) => {
        // Find the corresponding date input in the same container
        // .closest() finds the nearest parent with that class,
        // then .querySelector() finds the first .dateInput inside that parent.
        const dateContainerBody = checkbox.closest('.date_container_body');
        const dateInput = dateContainerBody.querySelector('.dateInput');

        if (checkbox.checked) {
            dateInput.removeAttribute('disabled');
            dateInput.setAttribute('value', dateInput.value);
        } else {
            dateInput.setAttribute('disabled', true);
            dateInput.value = ''; // Clear the value if the checkbox is unchecked
            dateInput.removeAttribute('value');
        }

        checkbox.addEventListener('change', function () {
            // Enable dateInput if checkbox is checked, otherwise disable
            if (this.checked) {
                dateInput.removeAttribute('disabled');
            } else {
                dateInput.setAttribute('disabled', true);
                dateInput.value = ''; // Clear the value if the checkbox is unchecked
                dateInput.removeAttribute('value');
            }
            // Update the sessionConcern field based on all checkboxes
            updateSessionConcern();
        });

    });

    // Update sessionConcern so that it reflects the default state.
    updateSessionConcern();
});


// Enable es6
/* jshint esversion: 6 */

// document.addEventListener('DOMContentLoaded', () => {
//     // Select all checkboxes with the "enableDateCheckbox" class
//     const checkboxes = document.querySelectorAll('.enableDateCheckbox');
//     const sessionConcern = document.querySelector('#session_concern'); // The one-and-only field

//     // Helper function: enable sessionConcern if at least one checkbox is checked
//     // checked checkbox INSIDE a .domain_intervention container
//     function updateSessionConcern() {
//         // Get all checkboxes inside .domain_intervention containers
//         const domainInterventionCheckboxes = document.querySelectorAll('.domain_intervention #audit_pedagogic.enableDateCheckbox');

//         // Check if ANY are checked
//         const anyCheckedInDomainIntervention = Array
//             .from(domainInterventionCheckboxes)
//             .some(checkbox => checkbox.checked);

//         if (anyCheckedInDomainIntervention) {
//             sessionConcern.removeAttribute('disabled');
//         } else {
//             sessionConcern.setAttribute('disabled', true);
//             sessionConcern.value = ''; // Clear the value if no checkboxes are checked
//             sessionConcern.removeAttribute('value');
//         }
//     }

//     checkboxes.forEach((checkbox) => {
//         // Find the corresponding date input in the same container
//         // .closest() finds the nearest parent with that class,
//         // then .querySelector() finds the first .dateInput inside that parent.
//         const dateContainerBody = checkbox.closest('.date_container_body');
//         const dateInput = dateContainerBody.querySelector('.dateInput');

//         if (checkbox.checked) {
//             dateInput.removeAttribute('disabled');
//             dateInput.setAttribute('value', dateInput.value);
//         } else {
//             dateInput.setAttribute('disabled', true);
//             dateInput.value = ''; // Clear the value if the checkbox is unchecked
//             dateInput.removeAttribute('value');
//         }

//         checkbox.addEventListener('change', function () {
//             // Enable dateInput if checkbox is checked, otherwise disable
//             if (this.checked) {
//                 dateInput.removeAttribute('disabled');
//             } else {
//                 dateInput.setAttribute('disabled', true);
//                 dateInput.value = ''; // Clear the value if the checkbox is unchecked
//                 dateInput.removeAttribute('value');
//             }
//             // Update the sessionConcern field based on all checkboxes
//             updateSessionConcern();

//             // Determine toggle value based on checkbox state
//             const toggleValue = this.checked ? 'checked' : 'unchecked';

//             // Use Fetch API to update the server-side state
//             fetch('lib.php', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/x-www-form-urlencoded'
//                 },
//                 body: `toggle=${encodeURIComponent(toggleValue)}`
//             })
//             .then(response => {
//                 if (!response.ok) {
//                     // If the response isn't OK, throw an error to be caught later
//                     throw new Error('Network response was not ok');
//                 }
//                 return response.json();
//             })
//             .then(data => {
//                 console.log('Server updated:', data);
//                 // Optionally check for server-side errors
//                 if (data.status !== 'success') {
//                     console.error('Server error:', data.message);
//                 }
//             })
//             .catch(error => {
//                 console.error('Fetch error:', error);
//             });
//         });
//     });

//     // Update sessionConcern so that it reflects the default state.
//     updateSessionConcern();
// });

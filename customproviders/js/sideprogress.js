// Description: This file is used to add active class to the current step in the side progress bar.
// The side progress bar is used to show the user the current step they are on in the form based on the url.

$(document).ready(() => {
    // Get all the steps in the side progress bar using the custom attribute data-step
    const steps = $('[data-step]');

    // Get the current url
    const windowUrl = $(location).attr("pathname");

    // Check the current url and add the active class to the step that corresponds to the current step
    switch (windowUrl) {
        case '/customdev/customproviders/':
            for (let i = 0; i < steps.length; i++) {
                const step = steps[i];
                if (step.getAttribute('data') === 'email') {
                    step.classList.add('active');
                }
            }
            break;
        case '/customdev/customproviders/information_form.php':
            for (let i = 0; i < steps.length; i++) {
                const step = steps[i];
                if (step.getAttribute('data') === 'information') {
                    step.classList.add('active');
                }
            }
            break;
        case '/customdev/customproviders/update_provider.php':
            for (let i = 0; i < steps.length; i++) {
                const step = steps[i];
                if (step.getAttribute('data') === 'information') {
                    step.classList.add('active');
                }
            }
            break;
        case '/customdev/customproviders/upload_documents.php':
            for (let i = 0; i < steps.length; i++) {
                const step = steps[i];
                if (step.getAttribute('data') === 'doc_administrative') {
                    step.classList.add('active');
                }
            }
            break;
        default:
            for (let i = 0; i < steps.length; i++) {
                const step = steps[i];
                if (step.getAttribute('data') === 'email') {
                    step.classList.add('active');
                }
            }
            break;
    }
});

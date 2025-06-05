/* jshint esversion: 6 */
// // This script is used to handle the tag selection in the custom providers page.
//
//
// const selectedTagsByGroup = {
//     domainExcellence: new Set(),
//     zones: new Set()
// };
//
//
// function updateHiddenInput(group) {
//     if (group === 'domainExcellence') {
//         document.getElementById('selectedDomains').value =
//             Array.from(selectedTagsByGroup.domainExcellence).join(',');
//     } else if (group === 'zones') {
//         document.getElementById('selectedZones').value =
//             Array.from(selectedTagsByGroup.zones).join(',');
//     }
// }
//
//
// // Add this function to update category states
// function updateCategoryState(categoryElement) {
//     const category = categoryElement.dataset.category;
//     const group = categoryElement.dataset.group;
//     const tags = document.querySelectorAll(`.tag[data-category="${category}"][data-group="${group}"]`);
//     const allSelected = Array.from(tags).every(tag => tag.classList.contains('selected'));
//
//     if (allSelected) {
//         categoryElement.classList.add('selected');
//     } else {
//         categoryElement.classList.remove('selected');
//     }
// }
//
//
// // When a category is clicked on, select all tags in that category
// document.querySelectorAll('.category').forEach(category => {
//     category.addEventListener('click', function () {
//
//         const group = this.dataset.group;
//         const category = this.dataset.category;
//         // Check if all these tags are already selected
//         const tags = document.querySelectorAll(`.tag[data-category="${category}"][data-group="${group}"]`);
//         const allSelected = Array.from(tags).every(tag => tag.classList.contains('selected'));
//
//         tags.forEach(tag => {
//             if (allSelected) {
//                 tag.classList.remove('selected');
//                 selectedTagsByGroup[group].delete(tag.textContent);
//             } else {
//                 tag.classList.add('selected');
//                 selectedTagsByGroup[group].add(tag.textContent);
//             }
//         });
//
//         updateHiddenInput(group);
//         updateCategoryState(this); // Update the clicked category's state
//     });
// });
//
//
// // When a tag is clicked on, toggle the selected state
// /**
//  * When an individual tag is clicked, toggle only that tag.
//  */
// document.querySelectorAll('.tag').forEach(tag => {
//     tag.addEventListener('click', function (e) {
//         // Prevent the click from also triggering the category’s click event
//         e.stopPropagation();
//
//         const group = this.dataset.group;
//         const category = this.dataset.category;
//
//         // Toggle the `selected` class
//         this.classList.toggle('selected');
//
//         if (this.classList.contains('selected')) {
//             selectedTagsByGroup[group].add(this.textContent);
//         } else {
//             selectedTagsByGroup[group].delete(this.textContent);
//         }
//
//         // Update hidden input & category state
//         updateHiddenInput(group);
//
//         const categoryElement = document.querySelector(`.category[data-category="${category}"][data-group="${group}"]`);
//         updateCategoryState(categoryElement);
//     });
// });


// Create sets to keep track of selected tags for each group
const selectedTagsByGroup = {
    domainExcellence: new Set(),
    zones: new Set()
};

// Update the corresponding hidden input based on the group
function updateHiddenInput(group) {
    if (group === 'domainExcellence') {
        document.getElementById('selectedDomains').value =
            Array.from(selectedTagsByGroup.domainExcellence).join(',');
    } else if (group === 'zones') {
        document.getElementById('selectedZones').value =
            Array.from(selectedTagsByGroup.zones).join(',');
    }
}

// Check all tags of a given category and update the category state
function updateCategoryState(categoryElement) {
    const category = categoryElement.dataset.category;
    const group = categoryElement.dataset.group;
    const tags = document.querySelectorAll(`.tag[data-category="${category}"][data-group="${group}"]`);
    const allSelected = Array.from(tags).every(tag => tag.classList.contains('selected'));

    if (allSelected) {
        categoryElement.classList.add('selected');
    } else {
        categoryElement.classList.remove('selected');
    }
}

// When a category element is clicked, toggle the selection of all tags within that category
document.querySelectorAll('.category').forEach(category => {
    category.addEventListener('click', function () {
        const group = this.dataset.group;
        const categoryName = this.dataset.category;
        const tags = document.querySelectorAll(`.tag[data-category="${categoryName}"][data-group="${group}"]`);
        // Determine if all tags in this category are already selected
        const allSelected = Array.from(tags).every(tag => tag.classList.contains('selected'));

        // Toggle each tag: deselect if all are selected, otherwise select all
        tags.forEach(tag => {
            if (allSelected) {
                tag.classList.remove('selected');
                selectedTagsByGroup[group].delete(tag.textContent.trim());
            } else {
                tag.classList.add('selected');
                selectedTagsByGroup[group].add(tag.textContent.trim());
            }
        });

        // Update the hidden input and category state
        updateHiddenInput(group);
        updateCategoryState(this);
    });
});

// When an individual tag is clicked, toggle its selected state
document.querySelectorAll('.tag').forEach(tag => {
    tag.addEventListener('click', function (e) {
        // Prevent the category click from triggering
        e.stopPropagation();

        const group = this.dataset.group;
        const category = this.dataset.category;

        // Toggle the selected class on the tag
        this.classList.toggle('selected');

        // Update the Set for this group accordingly
        if (this.classList.contains('selected')) {
            selectedTagsByGroup[group].add(this.textContent.trim());
        } else {
            selectedTagsByGroup[group].delete(this.textContent.trim());
        }

        // Update the hidden input and the category state
        updateHiddenInput(group);

        const categoryElement = document.querySelector(`.category[data-category="${category}"][data-group="${group}"]`);
        if (categoryElement) {
            updateCategoryState(categoryElement);
        }
    });
});

// Initialization function to set default selections based on hidden input values
function initializeSelections() {
    // For the domainExcellence group
    const selectedDomainsInput = document.getElementById('selectedDomains');
    if (selectedDomainsInput && selectedDomainsInput.value.trim() !== '') {
        const domains = selectedDomainsInput.value.split(',').map(item => item.trim());
        domains.forEach(domain => {
            // Find matching tags for domainExcellence group
            document.querySelectorAll(`.tag[data-group="domainExcellence"]`).forEach(tag => {
                if (tag.textContent.trim() === domain) {
                    tag.classList.add('selected');
                    selectedTagsByGroup.domainExcellence.add(domain);
                    const categoryElement = document.querySelector(`.category[data-category="${tag.dataset.category}"][data-group="domainExcellence"]`);
                    if (categoryElement) {
                        updateCategoryState(categoryElement);
                    }
                }
            });
        });
    }

    // For the zones group
    const selectedZonesInput = document.getElementById('selectedZones');
    if (selectedZonesInput && selectedZonesInput.value.trim() !== '') {
        const zones = selectedZonesInput.value.split(',').map(item => item.trim());
        zones.forEach(zone => {
            // Find matching tags for zones group
            document.querySelectorAll(`.tag[data-group="zones"]`).forEach(tag => {
                if (tag.textContent.trim() === zone) {
                    tag.classList.add('selected');
                    selectedTagsByGroup.zones.add(zone);
                    const categoryElement = document.querySelector(`.category[data-category="${tag.dataset.category}"][data-group="zones"]`);
                    if (categoryElement) {
                        updateCategoryState(categoryElement);
                    }
                }
            });
        });
    }
}

// Run initialization when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    initializeSelections();
});

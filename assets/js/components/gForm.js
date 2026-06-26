window.addEventListener("pageshow", function () {

    const fields = document.querySelectorAll('.gfield');

    if (fields.length) {

        fields.forEach(field => {
            const input = field.querySelector('.ginput_container input, .ginput_container textarea');
            if (!input) return;

            if (input.value.trim() !== "") {
                field.classList.add("inquiry__field-has-focus");
            } else {
                field.classList.remove("inquiry__field-has-focus");
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {

    // Check if gfield exists
    const fields = document.querySelectorAll('.gfield');
    if (fields.length) {

        fields.forEach(field => {

            // Get the actual input inside Gravity Form field
            const input = field.querySelector('.ginput_container input');

            // Proceed only if input exists
            if (!input) return;

            // Get ginput_container
            const container = field.querySelector('.ginput_container');

            // Create close (X) button
            const clearBtn = document.createElement('span');
            clearBtn.className = 'clear-btn';
            clearBtn.innerHTML = '&times;';

            // Append X inside ginput_container
            container.appendChild(clearBtn);

            // Clear input on click
            clearBtn.addEventListener('click', () => {
                input.value = "";
                input.blur();
                field.classList.remove('inquiry__field-has-focus');
            });
        });
    }

    const errorFields = document.querySelectorAll('.gform_validation_error .gfield');
    if (errorFields.length) {
        errorFields.forEach(errorField => {
            const inputFields = errorField.querySelectorAll('.ginput_container input, .ginput_container textarea');

            if (inputFields.length) {
                inputFields.forEach(inputField => {
                    console.log('inputField.value', inputField.value);
                    if (inputField.value.trim() !== "") {
                        errorField.classList.add("inquiry__field-has-focus");
                    } else {
                        errorField.classList.remove('inquiry__field-has-focus');
                    }
                });
            }
        });
    }

    const dropdownWrappers = document.querySelectorAll(".dropdown-item-wrapper");

    if (!dropdownWrappers.length) {
        console.warn("No dropdown-item-wrapper elements found.");
    } else {

        function closeAllDropdowns() {
            document.querySelectorAll(".dropdown-item-wrapper.open").forEach(wrapper => {
                wrapper.classList.remove("open");
            });

            document.querySelectorAll(".dropdown-items.open").forEach(menu => {
                menu.classList.remove("open");
            });
        }

        dropdownWrappers.forEach(wrapper => {

            const trigger = wrapper.querySelector(".dropdown-item-trigger");
            const itemsWrapper = wrapper.querySelector(".dropdown-items");
            const items = wrapper.querySelectorAll(".dropdown-item");
            const hiddenInput = wrapper.querySelector("input[type='hidden']");

            if (!trigger || !itemsWrapper || !hiddenInput) {
                console.warn("Missing required dropdown elements:", wrapper);
                return;
            }

            const initialValue = hiddenInput.value?.trim();

            if (initialValue) {
                const selectedItem = [...items].find(
                    item => item.dataset.value === initialValue
                );

                if (selectedItem) {
                    trigger.textContent = selectedItem.textContent.trim();
                    selectedItem.classList.add("selected");
                }
            }

            trigger.addEventListener("click", function (e) {
                e.stopPropagation();

                const isOpen = wrapper.classList.contains("open");

                closeAllDropdowns();

                if (!isOpen) {
                    wrapper.classList.add("open");
                    itemsWrapper.classList.add("open");
                }
            });

            items.forEach(item => {
                item.addEventListener("click", function (e) {
                    e.stopPropagation();

                    const value = item.dataset.value || "";
                    const text = item.textContent.trim();

                    trigger.textContent = text;

                    hiddenInput.value = value;

                    hiddenInput.dispatchEvent(
                        new Event("change", { bubbles: true })
                    );

                    items.forEach(i => i.classList.remove("selected"));
                    item.classList.add("selected");

                    wrapper.classList.remove("open");
                    itemsWrapper.classList.remove("open");
                });
            });
        });

        document.addEventListener("click", function () {
            closeAllDropdowns();
        });
    }
});

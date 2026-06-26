document.addEventListener("DOMContentLoaded", function () {
  const gfForm_body = document.querySelector(".footer__col--newsletter .gform-body");

  if (gfForm_body) {
    const inputs = gfForm_body.querySelectorAll("input, textarea, select");

    inputs.forEach(function (input) {
      input.addEventListener("focus", function () {
        const gfieldWrapper = input.closest(".gfield");
        if (gfieldWrapper) {
          gfieldWrapper.classList.add("inquiry__field-has-focus");
        }
      });

      input.addEventListener("blur", function () {
        const gfieldWrapper = input.closest(".gfield");
        if (gfieldWrapper) {
          if (input.value.trim() === "") {
            // Remove only when empty
            gfieldWrapper.classList.remove("inquiry__field-has-focus");
          }
        }
      });
    });
  }
});

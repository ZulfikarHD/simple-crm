import "./bootstrap";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Example usage in your JS:
Toastify({
    text: "This is a toast",
    duration: 3000,
}).showToast();

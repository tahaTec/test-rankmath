import {render} from "@wordpress/element";
import App from "./app";

document.addEventListener('DOMContentLoaded', function (e) {
    var element = document.getElementById('rankmath-test-widget');

    if (element && typeof element === "object") {
        render(<App/>, element);
    }
})

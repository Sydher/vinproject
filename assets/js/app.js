import '../css/app.scss';
import SearchWineFilter from './modules/SearchWineFilter';
import AdHandler from "./modules/AdHandler";

const $ = require("jquery");

$(document).ready(function () {
    // Configuration toast Bootstrap
    $(".toast").toast("show");

    // Configuration tooltip Bootstrap
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // Initialisation des modules
    new SearchWineFilter(document.querySelector('.js-filter'));
    new AdHandler(document.querySelector('#ad_special_button'));
});

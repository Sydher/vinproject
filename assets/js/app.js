import '../css/app.scss';
import SearchWineFilter from './modules/SearchWineFilter';

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
});

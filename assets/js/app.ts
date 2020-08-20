import '../css/app.scss';

const $ = require("jquery");

// Configuration toast Bootstrap
$(document).ready(function () {
    $(".toast").toast("show");
});

// Configuration tooltip Bootstrap
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

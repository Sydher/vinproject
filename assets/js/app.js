import '../css/app.scss';
import noUiSlider from 'nouislider';
import 'nouislider/distribute/nouislider.css';
import Filter from './modules/Filter';

new Filter(document.querySelector('.js-filter'));

const $ = require("jquery");

// Configuration toast Bootstrap
$(document).ready(function () {
    $(".toast").toast("show");
});

// Configuration tooltip Bootstrap
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

// Configuration No UI Slider
const slider = document.getElementById('price_slider');
if (slider) {
    let actualMin = document.getElementById('min');
    let actualMax = document.getElementById('max');
    const minValue = Math.floor(parseInt(slider.dataset.min) / 10) * 10;
    const maxValue = Math.ceil(parseInt(slider.dataset.max) / 10) * 10;

    let range = noUiSlider.create(slider, {
        start: [parseInt(actualMin.value) || minValue, parseInt(actualMax.value) || maxValue],
        connect: true,
        range: {
            'min': minValue,
            'max': maxValue
        },
        step: 10,
        margin: 10
    });

    range.on('slide', function (values, handle) {
        if (handle === 0) {
            actualMin.value = String(Math.round(values[0]));
        }
        if (handle === 1) {
            actualMax.value = String(Math.round(values[1]));
        }
    });
    range.on('end', function (values, handle) {
        actualMin.dispatchEvent(new Event('change'));
    });
}

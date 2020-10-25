/**
 * @property {HTMLElement} form
 * @property {HTMLElement} sorting
 * @property {HTMLElement} content
 * @property {HTMLElement} pagination
 */
export default class Filter {

    /**
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return;
        }
        this.form = element.querySelector('.js-filter-form');
        this.sorting = element.querySelector('.js-filter-sorting');
        this.content = element.querySelector('.js-filter-content');
        this.pagination = element.querySelector('.js-filter-pagination');
        this.bindEvents();
    }

    /**
     * Ajoute les comportements aux différents éléments.
     */
    bindEvents() {
        console.log("coucou")
        this.sorting.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
            })
        });
    }
}
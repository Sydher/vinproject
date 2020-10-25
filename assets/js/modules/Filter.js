import {Flipper, spring} from 'flip-toolkit';

/**
 * @property {HTMLFormElement} form
 * @property {HTMLElement} sorting
 * @property {HTMLElement} content
 * @property {HTMLElement} pagination
 * @property {number} page
 * @property {boolean} moreNav
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
        this.page = parseInt(new URLSearchParams(window.location.search).get('page') || 1);
        this.moreNav = this.page === 1;
        this.bindEvents();
    }

    /**
     * Ajoute les comportements aux différents éléments.
     */
    bindEvents() {
        /**
         * Evénement lors d'un clic sur un lien.
         * @param e l'événement
         */
        const aClickListener = e => {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'));
            }
        }

        // Ajout d'un événement sur le tri
        this.sorting.addEventListener('click', e => {
            aClickListener(e);
            this.page = 1;
        });

        // Si chargement dynamique du contenu supplémentaire
        if (this.moreNav) {
            // Ajout d'un bouton 'Voir plus'
            this.pagination.innerHTML = '<button class="btn btn-primary">Voir plus</button>'
            this.pagination.querySelector('button')
                .addEventListener('click', this.loadMore.bind(this));
        }
        // Sinon chargement de la pagination
        else {
            this.pagination.addEventListener('click', aClickListener);
        }

        // Ajout d'un événement sur les filtres
        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this));
        });
    }

    /**
     * Charge le contenu de l'URL.
     * @param url l'url à charger
     * @param append true si chargement dynamique du contenu supplémentaire, false sinon
     * @returns {Promise<void>}
     */
    async loadUrl(url, append = false) {
        this.showLoader();

        // Défini l'appel en mode Ajax
        const params = new URLSearchParams(url.split('?')[1] || '');
        params.set('ajax', 1);

        // Appel à l'API
        const response = await fetch(url.split('?')[0] + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Si la réponse de l'API est valide
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();

            // Charge le contenu du tri
            this.sorting.innerHTML = data.sorting;

            // Remplace les éléments du contenu
            this.flipContent(data.content, append);

            // Charge la pagination
            if (!this.moreNav) {
                this.pagination.innerHTML = data.pagination;
            } else if (this.page === data.pages) {
                this.pagination.style.display = 'none';
            } else {
                this.pagination.style.display = null;
            }

            this.updatePrices(data);

            // Retire le paramètre Ajax pour garder une URL valide
            params.delete('ajax');

            // Redéfini l'URL dans le navigateur
            history.replaceState({}, '', url.split('?')[0] + '?' + params.toString());
        } else {
            console.error(response);
        }

        this.hideLoader();
    }

    /**
     * Charge dynamiquement du contenu supplémentaire.
     * @returns {Promise<void>}
     */
    async loadMore() {
        const button = this.pagination.querySelector('button');
        button.setAttribute('disabled', 'disabled');

        this.page++;
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        params.set('page', this.page);
        await this.loadUrl(url.pathname + '?' + params.toString(), true);

        button.removeAttribute('disabled');
    }

    /**
     * Charge le formulaire de filtre.
     * @returns {Promise<void>}
     */
    async loadForm() {
        this.page = 1;
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') || window.location.href);
        const params = new URLSearchParams();
        data.forEach((value, key) => {
            params.append(key, value);
        });
        return this.loadUrl(url.pathname + '?' + params.toString());
    }

    /**
     * Remplace les éléments du contenu avec un effet d'animation.
     * @param {string} content
     * @param {boolean} append
     */
    flipContent(content, append) {
        // Animation
        const springAnimType = "stiff";
        const exitSpring = (element, index, onComplete) => {
            spring({
                config: springAnimType,
                values: {
                    translateY: [0, -20],
                    opacity: [1, 0]
                },
                onUpdate: ({translateY, opacity}) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                },
                onComplete
            });
        };
        const appearSpring = (element, index) => {
            spring({
                config: springAnimType,
                values: {
                    translateY: [20, 0],
                    opacity: [0, 1]
                },
                onUpdate: ({translateY, opacity}) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                }
            });
        };

        const flipper = new Flipper({
            element: this.content
        });
        this.content.querySelectorAll('.search-product-card').forEach(element => {
            flipper.addFlipped({
                element,
                spring: "gentle",
                flipId: element.id,
                shouldFlip: false,
                onExit: exitSpring
            });
        });
        flipper.recordBeforeUpdate();
        if (append) {
            this.content.innerHTML += content;
        } else {
            this.content.innerHTML = content;
        }
        this.content.querySelectorAll('.search-product-card').forEach(element => {
            flipper.addFlipped({
                element,
                spring: "gentle",
                flipId: element.id,
                onAppear: appearSpring
            });
        });
        flipper.update();
    }

    /**
     * Affiche l'indicateur de chargement.
     */
    showLoader() {
        this.form.classList.add('is-loading');
        const loader = this.form.querySelector('.js-filter-loading');
        if (loader === null) {
            return;
        }
        loader.setAttribute('aria-hidden', 'false');
        loader.style.display = null;
    }

    /**
     * Masque l'indicateur de chargement.
     */
    hideLoader() {
        this.form.classList.remove('is-loading');
        const loader = this.form.querySelector('.js-filter-loading');
        if (loader === null) {
            return;
        }
        loader.setAttribute('aria-hidden', 'true');
        loader.style.display = 'none';
    }

    /**
     * Mets à jour le sélecteur de prix.
     * @param min le nouveau prix minimal
     * @param max le nouveau prix maximal
     */
    updatePrices({min, max}) {
        const slider = document.querySelector('price-slider');
        if (slider === null) {
            return;
        }
        slider.noUiSlider.updateOptions({
            range: {min: [min], max: [max]}
        });
    }

}

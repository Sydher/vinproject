export default class AdHandler {

    /**
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return;
        }

        // Génère un id pour l'heure en cours
        let now = new Date();
        let ad_hide_tmp = 'ad_hide' + now.getDate() + now.getMonth() + now.getHours();

        // Affiche l'annonce si elle n'a pas déjà été masquée dans l'heure
        if (sessionStorage.getItem(ad_hide_tmp) === null) {
            $(".ad_special").removeClass('hidden');
        }

        // Masque l'annonce pour 1h
        element.addEventListener('click', e => {
            sessionStorage.setItem(ad_hide_tmp, 'true');
        });
    }

}
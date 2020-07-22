import '../css/app.scss';

/**
 * Gestion de la barre de navigation.
 */
document.getElementById("burger").onclick = () => {
    document.getElementById("burger").classList.toggle("is-active")
    document.getElementById("navbarVinProject").classList.toggle("is-active")
}

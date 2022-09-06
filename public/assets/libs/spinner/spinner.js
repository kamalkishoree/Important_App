/**
 * custom spinner Js 
 */
const spinnerJS = {
    showSpinner() {
        $('.nb-spinner-main').addClass('show-nb-spinner-main');
    },
    hideSpinner(){
        setTimeout(() => {
            $('.nb-spinner-main').removeClass('show-nb-spinner-main');
        },800);
    }
}
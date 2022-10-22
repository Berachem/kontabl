document.addEventListener('alpine:init', () => {
    Alpine.data('search', ($router) => ({
        init() {
            // detect if user is logged in
            // if not logged in, show login page
            $router.push('/login');
        }
    }));

    Alpine.data('login', () => ({
        errMsg: "",

        login() {
            // request here
            // recover message from server
            this.errMsg = "Identifiant ou mot de passe incorrect";
        }
    }));
});
document.addEventListener('alpine:init', () => {
    Alpine.data('search', ($router) => ({
        userType: localStorage.getItem('userType'),
        selectedTab: 'tr',

        init() {
            // detect if user is logged in
            // if not logged in, show login page
            // $router.push('/login');
        }
    }));

    Alpine.data('login', ($router) => ({
        errMsg: "",
        user: "",
        password: "",

        async login() {
            // send data via POST params
            const formData = new FormData();
            formData.append('nom', this.user);
            formData.append('mdp', this.password);
            const res = await fetch('/api/?action=login', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json());
            if (res.success) {
                localStorage.setItem('userType', res.type);
                $router.push('/search');
                return;
            }
            this.errMsg = res.error || "";
        }
    }));
});

document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (!hash) {
        window.location.hash = '#/';
    }
});
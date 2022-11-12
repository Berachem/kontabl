const _logout = async () => {
    const res = await fetch('/api/?action=logout').then(x => x.json());
    if (res.success) {
        window.location.href = '/';
    } else {
        alert("Erreur au moment de la déconnexion");
    }
};

const _isLoggedIn = async () => {
    const res = await fetch('/api/?action=isLoggedIn').then(x => x.json());
    return res.isLogged;
};

document.addEventListener('alpine:init', () => {
    Alpine.data('search', ($router) => ({
        userType: localStorage.getItem('userType'),
        selectedTab: 'tr',
        sirenNumbers: [],
        siren: "",
        socialName: "",
        date: "",
        results: [],
        loading: false,

        async init() {
            if (!await _isLoggedIn()) {
                $router.push('/login?reqauth=1');
            }

            const res = await fetch('/api/?action=treasuryDataTable').then(x => x.json());
            if (res.success) {
                this.sirenNumbers = res.data.map(x => x.NumSiren);
            }
        },

        async search() {
            this.loading = true;
            this.results = [];
            if (this.selectedTab === 'tr') {
                const res = await fetch(`/api/?action=treasuryDataTable&numSiren=${this.siren}&raisonSociale=${this.socialName}&date=${this.date}`).then(x => x.json());
                if (res.success) {
                    this.results = res.data;
                }
            }
            this.loading = false;
        },

        async logout() {
            await _logout();
        }
    }));

    Alpine.data('login', ($router) => ({
        errMsg: "",
        user: "",
        password: "",
        inputType: "password",

        init() {
            const urlParams = new URLSearchParams(window.location.href.split('?')[1]);
            if (urlParams.has('reqauth')) {
                this.errMsg = "Vous devez vous connecter pour accéder à cette page";
            }
        },

        async login() {
            // send data via POST params
            const formData = new FormData();
            formData.append('login', this.user);
            formData.append('password', this.password);
            const res = await fetch('/api/?action=login', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json());
            if (res.success) {
                localStorage.setItem('userType', res.type);
                $router.push('/');
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
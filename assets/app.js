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
        dateBefore: "",
        dateAfter: "",
        unpaidNumber: "",
        results: [],
        transactions: [],
        unpaid: [],
        loading: false,

        formatDate(dateString) {
            const date = new Date(dateString);
            var months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            return "" + date.getDate() + " " + months[date.getMonth()] + " " + date.getFullYear();
        },

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
            let res;
            switch (this.selectedTab) {
                case 'tr':
                    res = await fetch(`/api/?action=treasuryDataTable&numSiren=${this.siren}&raisonSociale=${this.socialName}&date=${this.date}`).then(x => x.json());
                    if (res.success) {
                        this.results = res.data;
                    }
                    break;
                case 're':
                    res = await fetch(`/api/?action=discountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}`).then(x => x.json());
                    if (res.success) {
                        this.transactions = res.data;
                    }
                    break;
                case 'im':
                    res = await fetch(`/api/?action=unPaidDiscountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}&numDossierImpaye=${this.unpaidNumber}`).then(x => x.json());
                    if (res.success) {
                        this.unpaid = res.data;
                    }
                    break;
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
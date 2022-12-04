const _logout = async () => {
    const res = await fetch('/api/?action=logout').then(x => x.json());
    if (res.success) {
        window.location.href = '/#/login';
        window.location.reload();
    } else {
        alert("Erreur au moment de la déconnexion");
    }
};

const _getToken = () => {
    return document.querySelector('meta[name="_token"]').content;
};

const _isLoggedIn = async () => {
    const res = await fetch('/api/?action=isLoggedIn').then(x => x.json());
    return res.isLogged;
};

const _downloadBlob = (blob, filename) => {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
};

document.addEventListener('alpine:init', () => {
    Alpine.data('search', ($router) => ({
        userType: localStorage.getItem('userType'),
        selectedTab: '',
        sirenNumbers: [],
        siren: "",
        socialName: "",
        date: "",
        dateBefore: "",
        dateAfter: "",
        numDiscount: "",
        unpaidNumber: "",
        results: [],
        transactions: [],
        unpaids: [],
        loading: false,
        prevOrderDir: -1,
        linkedTransactions: [],
        totalMontantUnpaid: 0,
        loadingLinkedTransactions: false,
        detailsModal: null,
        rowsCount: 10,
        page: 1,
        alreadyOpenedTabs: new Set(),
        exportTypeLoading: '',

        formatDate(dateString) {
            const date = new Date(dateString);
            var months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            return "" + date.getDate() + " " + months[date.getMonth()] + " " + date.getFullYear();
        },

        loadNetworkImage(code) {
            return '/img/' + code.toUpperCase() + '.png';
        },

        // TODO: Montant total stocké en absolu, ne marche pas avec le tri actuel.
        orderTableBy(array, column) {
            const direction = this.prevOrderDir === 1 ? -1 : 1;
            array.sort((a, b) => {
                a = parseInt(a[column]) || a[column];
                b = parseInt(b[column]) || b[column];
                return a > b ? direction : a < b ? -direction : 0;
            });
            this.prevOrderDir = direction;
        },

        openTab(tabId) {
            this.selectedTab = tabId;
            if (!this.alreadyOpenedTabs.has(tabId)) {
                this.search();
            }
            this.alreadyOpenedTabs.add(tabId);
        },

        async init() {
            if (!await _isLoggedIn()) {
                $router.push('/login?reqauth=1');
            }

            this.openTab('tr');

            const res = await fetch('/api/?action=treasuryDataTable').then(x => x.json());
            if (res.success) {
                this.sirenNumbers = res.data.map(x => x.NumSiren);
            }

            const pieRes = await fetch('/api/?action=graphicsLabels').then(x => x.json());
            if (!pieRes.success) return;
            const data = [];
            Object.keys(pieRes.data).forEach(function (key) {
                data.push({ name: key, y: pieRes.data[key] });
            });
            Highcharts.chart('highcharts-pie-unpaids', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Motifs impayés',
                    colorByPoint: true,
                    data
                }]
            });

            const lineRes = await fetch('/api/?action=graphics').then(x => x.json());
            const monthsNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            if (!lineRes.success) return;
            Highcharts.chart('highcharts-line-discounts', {

                title: {
                    text: ''
                },

                yAxis: {
                    title: {
                        text: 'Montant total par mois'
                    }
                },

                xAxis: {
                    accessibility: {
                        rangeDescription: 'Mois'
                    },
                    categories: lineRes.mois.map(x => x = monthsNames[parseInt(x) - 1])
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                        pointStart: 0
                    }
                },

                series: [{
                    name: 'Remises',
                    data: lineRes.montant
                }],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });
        },

        async search() {
            this.loading = true;
            this.results = [];
            let res;
            switch (this.selectedTab) {
                case 'tr':
                    res = await fetch(`/api/?action=treasuryDataTable&numSiren=${this.siren}&raisonSociale=${this.socialName}&date=${this.date}`).then(x => x.json());
                    if (!res.success) return;
                    this.results = res.data;

                    Highcharts.chart('highcharts-histogram-treasury', {
                        chart: {
                            type: 'column',
                            height: 600,
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            categories: res.data.map(x => x.RaisonSociale),
                            crosshair: true
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }
                        },
                        series: [{
                            name: 'Montant de la trésorerie',
                            data: res.data.map(x => +x.MontantTotal)
                        }]
                    });
                    console.log(res.data.map(x => +x.MontantTotal));
                    break;
                case 're':
                    res = await fetch(`/api/?action=discountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}&numRemise=${this.numDiscount}`).then(x => x.json());
                    if (res.success) {
                        this.transactions = res.data.map(x => {
                            return { ...x, MontantTotal: +(x.Sens + x.MontantTotal) };
                        });
                    }
                    break;
                case 'im':
                    res = await fetch(`/api/?action=unPaidDiscountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}&numDossierImpaye=${this.unpaidNumber}`).then(x => x.json());
                    if (res.success) {
                        this.unpaids = res.data;
                    }
                    break;
            }
            this.loading = false;
        },

        async loadDetailsForSiren(siren) {
            this.$refs.detailsModal.showModal();
            this.loadingLinkedTransactions = true;
            this.linkedTransactions = [];
            const res = await fetch(`/api/?action=detailsTransactions&numSiren=${siren}`).then(x => x.json());
            if (res.success) {
                this.linkedTransactions = res.transactions;
            }
            this.loadingLinkedTransactions = false;
        },

        // Calculer le montant total des impayés
        calculateTotalUnpaids() {
            let total = 0;
            this.unpaids.forEach(x => {
                total += x.MontantTotal;
            });
            this.totalMontantUnpaid = total;
        },

        getTotalAmount() {
            return this.totalMontantUnpaid;
        },

        async exportTableIn(tableSelector, fileType) {
            this.exportTypeLoading = fileType;
            const table = document.querySelector(tableSelector);
            const tableHeaders = [];
            const tableRows = [];
            table.querySelectorAll('thead th').forEach(th => tableHeaders.push(th.innerText));
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => row.push(td.innerText));
                tableRows.push(row);
            });
            console.log(tableHeaders, tableRows);
            const csvText =
                tableHeaders.join(';') + ';exporté le ' + this.formatDate(+new Date()) + '\n' +
                tableRows.map(x => x.join(';')).join('\n');
            switch (fileType) {
                case 'csv':
                    const csvBlob = new Blob([csvText], { type: 'text/csv' });
                    _downloadBlob(csvBlob, 'export.csv');
                    break;
                case 'xlsx':
                    const formData = new FormData();
                    formData.append('csvString', csvText);
                    formData.append('_token', _getToken());
                    const req = await fetch('/api/?action=csvToXls', {
                        method: 'POST',
                        body: formData
                    });
                    const xlsBlob = await req.blob();
                    _downloadBlob(xlsBlob, 'export.xlsx');
                    break;
                case 'pdf':
                    window.print();
                    break;
            }
            this.exportTypeLoading = '';
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
            formData.append('_token', _getToken());
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

    Alpine.data('merchant', ($router) => ({
        userType: localStorage.getItem('userType'),
        merchants: [],
        merchantsTemp: [],
        selectedTab: '',

        socialName: '',
        siren: '',
        currency: '',
        numCard: '',
        network: '',
        password: '',
        idLogin: '',
        invalidCardNumber: false,

        openTab(tabId) {
            this.selectedTab = tabId;
        },

        _validateCardNumber(number) {
            var regex = new RegExp("^[0-9]{16}$");
            if (!regex.test(number))
                return false;
            return this._luhnCheck(number);
        },

        _luhnCheck(val) {
            var sum = 0;
            for (var i = 0; i < val.length; i++) {
                var intVal = parseInt(val.substr(i, 1));
                if (i % 2 == 0) {
                    intVal *= 2;
                    if (intVal > 9) {
                        intVal = 1 + (intVal % 10);
                    }
                }
                sum += intVal;
            }
            return (sum % 10) == 0;
        },

        processCardNumber() {
            switch (true) {
                case /^4/.test(this.numCard):
                    this.network = 'VS';
                    break;
                case /^5[1-5]/.test(this.numCard):
                    this.network = 'MS';
                    break;
                case /^3[47]/.test(this.numCard):
                    this.network = 'AE';
                    break;
                default:
                    this.network = 'XX';
                    break;
            }
            this.invalidCardNumber = !this._validateCardNumber(this.numCard);
        },

        async createMerchantTemp() {
            const formData = new FormData();
            formData.append('raisonSociale', this.socialName);
            formData.append('siren', this.siren);
            formData.append('currency', this.currency);
            formData.append('numCarte', this.numCard);
            formData.append('network', this.network);
            formData.append('idLogin', this.idLogin);
            formData.append('password', this.password);
            formData.append('_token', _getToken());

            const resJson = await fetch('/api/?action=createMerchantTemporarily', {
                method: 'POST',
                body: formData
            }).then(x => x.json());

            if (resJson.error || !resJson.success) {
                alert(resJson.error || "Une erreur est survenue");
                return;
            }

            if (resJson.success) {
                this.merchantsTemp.push(resJson.merchant);
                this.socialName = '';
                this.siren = '';
                this.currency = '';
                this.numCard = '';
                this.network = '';
                this.idLogin = '';
                this.password = '';
                alert('Le marchand à bien été créé');
            }
        },

        async deleteMerchant(siren) {
            if (!confirm('Voulez-vous vraiment supprimer ce marchand ?')) return;

            const formData = new FormData();
            formData.append('numSiren', siren);
            formData.append('_token', _getToken());

            const resJson = await fetch(`/api/?action=deleteAcount`, {
                method: 'POST',
                body: formData
            }).then(x => x.json());

            if (resJson.needRefresh) {
                alert('Page expirée.');
                location.reload();
                return;
            }

            if (resJson.error) {
                alert(resJson.error);
                return;
            }

            if (resJson.success) {
                this.merchants = this.merchants.filter(x => x.numSiren !== siren);
                this.merchantsTemp = this.merchantsTemp.filter(x => x.numSiren !== siren);
            }
        },

        async acceptMerchantTemp(siren) {
            if (!confirm('Voulez-vous vraiment accepter ce marchand ?')) return;

            const formData = new FormData();
            formData.append('numSiren', siren);
            formData.append('_token', _getToken());

            let resJson = await fetch(`/api/?action=acceptMerchantTemp&numSiren=${siren}`).then(x => x.json());

            console.log(resJson);

            if (resJson.needRefresh) {
                alert('Page expirée.');
                location.reload();
                return;
            }

            if (resJson.error) {
                alert(resJson.error);
                return;
            }

            if (resJson.success) {
                this.merchantsTemp = this.merchantsTemp.filter(x => x.numSiren !== siren);
                this.merchants.push(resJson.merchant);
            }
        },

        async init() {
            if (this.userType == 'admin') this.openTab('merchantsTemp');
            else if (this.userType == 'productowner') this.openTab('merchants');

            if (!await _isLoggedIn()) {
                $router.push('/login?reqauth=1');
            }

            let res;
            res = await fetch('/api/?action=getAllAcount').then(x => x.json());
            if (!res.success) return;
            this.merchants = res.data;

            let resTemp = await fetch('/api/?action=getAllAcountTemp').then(x => x.json());
            if (!resTemp.success) return;
            this.merchantsTemp = resTemp.data;
        },

    }));
});

document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (!hash) {
        window.location.hash = '#/';
    }
});
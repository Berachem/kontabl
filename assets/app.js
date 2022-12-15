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
    Alpine.data('main', () => ({
        hash: window.location.hash,

        init() {
            this.hash = window.location.hash;

            window.addEventListener('hashchange', () => {
                this.hash = window.location.hash;
            });
        }
    }));

    Alpine.data('search', ($router) => ({
        name: localStorage.getItem('name'),
        userType: localStorage.getItem('userType'),
        userTypeTitle: localStorage.getItem('userTypeTitle'),
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

        paginate(array) {
            return array.slice((this.page - 1) * this.rowsCount, this.page * this.rowsCount);
        },

        totalForCurrentPaginationOf(array) {
            return this.paginate(array).reduce((acc, x) => acc + (x.MontantTotal || parseInt(x.Montant)), 0);
        },

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

            const pieRes = await fetch('/api/?action=graphicsLabels').then(x => x.json());
            if (!pieRes.success) return;


            const lineRes = await fetch('/api/?action=graphics').then(x => x.json());
            const monthsNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            if (!lineRes.success) return;
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

                    if (this.sirenNumbers.length === 0) {
                        this.sirenNumbers = res.data.map(x => x.NumSiren);
                    }

                    // histogramme si c'est un Product Owner
                    if (this.results.length > 1) {
                        Highcharts.chart('highcharts-histogram-treasury', {
                            chart: {
                                type: 'column',
                                height: 600,
                            },
                            // set color red for negative values
                            plotOptions: {
                                series: {
                                    colorByPoint: true,
                                    colors: [
                                        '#7cb5ec',
                                        '#434348',
                                        '#90ed7d',
                                        '#f7a35c',
                                        '#8085e9',
                                        '#f15c80',
                                        '#e4d354',
                                        '#8085e8',
                                        '#8d4653'],
                                    dataLabels: {
                                        enabled: true,
                                        format: '{point.y:.2f} €'
                                    }
                                }
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
                    }
                    // C'est un marchand
                    else { //if (res.data.length == 1)

                        var value = this.results[0].MontantTotal;
                        var currency = this.results[0].Devise;


                        var gaugeOptions = {
                            chart: {
                                type: 'solidgauge'
                            },

                            title: null,

                            pane: {
                                center: ['50%', '85%'],
                                size: '140%',
                                startAngle: -90,
                                endAngle: 90,
                                background: {
                                    backgroundColor:
                                        Highcharts.defaultOptions.legend.backgroundColor || (value > 0 ? '#EEE' : 'red'),
                                    innerRadius: '60%',
                                    outerRadius: '100%',
                                    shape: 'arc'
                                }
                            },

                            exporting: {
                                enabled: true
                            },

                            tooltip: {
                                enabled: true
                            },

                            // the value axis
                            yAxis: {
                                stops: [
                                    [0.1, 'orange'], // green
                                    [0.5, '#DDDF0D'], // yellow
                                    [0.9, 'green'] // red
                                ],
                                lineWidth: 0,
                                tickWidth: 0,
                                minorTickInterval: null,
                                tickAmount: 2,
                                title: {
                                    y: -70
                                },
                                labels: {
                                    y: 16
                                }
                            },

                            plotOptions: {
                                solidgauge: {
                                    dataLabels: {
                                        y: 5,
                                        borderWidth: 0,
                                        useHTML: true
                                    }
                                }
                            }
                        };

                        // The speed gauge
                        var chartSpeed = Highcharts.chart('highcharts-speed-treasury', Highcharts.merge(gaugeOptions, {
                            yAxis: {
                                min: 0,
                                max: 5000,
                                title: {
                                    text: 'Solde'
                                }
                            },

                            credits: {
                                enabled: false
                            },

                            series: [{
                                name: 'Montant de la trésorerie',
                                data: [value],
                                dataLabels: {
                                    format:
                                        '<div style="text-align:center">' +
                                        '<span style="font-size:25px">{y} ' + currency + '</span><br/>' +
                                        '<span style="font-size:12px;opacity:0.4">jauge de votre solde</span>' +
                                        '</div>'
                                },
                                tooltip: {
                                    valueSuffix: ' ' + currency
                                }
                            }]

                        }));

                    }

                    break;
                case 're':
                    res = await fetch(`/api/?action=discountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}&numRemise=${this.numDiscount}&raisonSociale=${this.socialName}`).then(x => x.json());
                    const transactionsAmountByMonth = [];
                    if (res.success) {
                        this.transactions = res.data.map(x => {
                            return { ...x, MontantTotal: +(x.Sens + x.MontantTotal) };
                        });
                        let sumForMonth = 0;
                        for (let i = 1; i <= 12; i++) {
                            sumForMonth = this.transactions
                                .filter(x => new Date(x.DateTraitement)
                                    .getMonth() === i - 1)
                                .map(x => x.MontantTotal)
                                .reduce((a, b) => a + b, 0);
                            transactionsAmountByMonth.push(sumForMonth);
                        }
                    }

                    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

                    Highcharts.chart('highcharts-line-discounts', {
                        chart: {
                            type: 'spline'
                        },
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
                            categories: months
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
                                pointStart: 0,
                                // set color red for negative values
                                colorByPoint: true,

                            }
                        },
                        credits: {
                            enabled: false
                        },

                        series: [{
                            name: 'Remises',
                            data: transactionsAmountByMonth
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
                    break;
                case 'im':
                    res = await fetch(`/api/?action=unPaidDiscountDataTable&date_debut=${this.dateAfter}&date_fin=${this.dateBefore}&numDossierImpaye=${this.unpaidNumber}`).then(x => x.json());
                    if (res.success) {
                        this.unpaids = res.data;

                        // LibImpayé PIE CHART
                        let unpaidReasons = res.data.map(x => x.LibImpayé);
                        var unpaidReasonsOccurences = {};
                        for (var i = 0; i < unpaidReasons.length; i++) {
                            unpaidReasonsOccurences[unpaidReasons[i]] = (unpaidReasonsOccurences[unpaidReasons[i]] || 0) + 1;
                        }
                        console.log(unpaidReasonsOccurences);

                        let unpaidReasonsData = [];

                        Object.keys(unpaidReasonsOccurences).forEach(function (key) {
                            unpaidReasonsData.push({ name: key, y: unpaidReasonsOccurences[key] });
                        });
                        console.log(unpaidReasonsData);

                        if (unpaidReasonsData.length > 0)
                            Highcharts.chart('highcharts-pie-unpaids-reasons', {
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
                                    data: unpaidReasonsData
                                }],
                                credits: {
                                    enabled: false
                                },
                            });


                        // NETWORK CARD TREEMAP
                        let networkCards = res.data.map(x => x.Reseau);
                        var networkCardsOccurences = {};
                        for (var i = 0; i < networkCards.length; i++) {
                            networkCardsOccurences[networkCards[i]] = (networkCardsOccurences[networkCards[i]] || 0) + 1;
                        }
                        console.log(networkCardsOccurences);

                        let networkCardsData = [];
                        const shortNetworkCardsToLong = { "VS": "Visa", "MC": "MasterCard", "AE": "American Express", "CB": "Carte Bleue", "DC": "Diners Club", "JCB": "JCB", "OT": "Autres" };

                        Object.keys(networkCardsOccurences).forEach(function (key) {
                            networkCardsData.push({ name: shortNetworkCardsToLong[key], value: networkCardsOccurences[key], colorValue: networkCardsOccurences[key] });
                        });


                        if (networkCardsData.length > 0)
                            Highcharts.chart('highcharts-treemap-unpaids-networks', {
                                colorAxis: {
                                    minColor: '#FFFFFF',
                                    maxColor: Highcharts.getOptions().colors[2]
                                },
                                series: [{
                                    type: 'treemap',
                                    layoutAlgorithm: 'squarified',
                                    colorByPoint: true,
                                    data: networkCardsData
                                }],
                                title: {
                                    text: ''
                                },
                                credits: {
                                    enabled: false
                                },
                            });

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
            const moneyCols = new Set();
            table.querySelectorAll('thead th').forEach((th, i) => {
                tableHeaders.push(th.innerText);
                if (/montant/i.test(th.innerText))
                    moneyCols.add(i);
            });
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach((td, i) => row.push(
                    moneyCols.has(i) ? parseFloat(td.innerText.replace(/ /g, '').replace(',', '.')) : td.innerText
                ));
                tableRows.push(row);
            });
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
            if (!confirm('Voulez-vous vraiment vous déconnecter ?')) return;
            await _logout();
        }
    }));

    Alpine.data('login', ($router) => ({
        turnstileLoaded: false,
        sending: false,
        errMsg: "",
        user: "",
        password: "",
        inputType: "password",
        needLoginToContinue: false,

        init() {
            const urlParams = new URLSearchParams(window.location.href.split('?')[1]);
            if (urlParams.has('reqauth')) {
                this.needLoginToContinue = true;
            }

            setInterval(() => {
                this.turnstileLoaded = !!window.turnstile;
            }, 1000);
        },

        defaultTurnstileCallback(message) {
            this.sending = false;
            this.errMsg = message || "";
            this.$refs.turnstileDialog.close();
        },

        async login() {
            this.sending = true;
            this.$refs.turnstileDialog.showModal();
            window.turnstile.render('.cf-turnstile', {
                sitekey: '0x4AAAAAAABhUS_rVuucp3jB',
                callback: (turnstileToken) => {
                    this._login(turnstileToken);
                    this.defaultTurnstileCallback();
                },
                "error-callback": () => {
                    this.defaultTurnstileCallback("Une erreur est survenue. Veuillez réessayer.");
                },
                "timeout-callback": () => {
                    this.defaultTurnstileCallback("Le délai de validation a été dépassé. Veuillez réessayer.");
                },
                "expired-callback": () => {
                    this.defaultTurnstileCallback("Le délai de validation a été dépassé. Veuillez réessayer.");
                }
            });
        },

        async _login(turnstileToken) {
            // send data via POST params
            const formData = new FormData();
            formData.append('login', this.user);
            formData.append('password', this.password);
            formData.append('_token', _getToken());
            formData.append('turnstileToken', turnstileToken);
            const res = await fetch('/api/?action=login', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json());
            if (res.success) {
                localStorage.setItem('name', res.name);
                localStorage.setItem('userType', res.type);
                if (res.type === 'user') {
                    $router.push('/');
                    localStorage.setItem('userTypeTitle', "Commerçant");
                } else if (res.type === 'admin') {
                    $router.push('/merchants');
                    localStorage.setItem('userTypeTitle', "Administrateur");
                } else if (res.type === 'productowner') {
                    $router.push('/');
                    localStorage.setItem('userTypeTitle', "Product Owner");
                }

                return;
            }
            this.user = "";
            this.password = "";
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
        numCard: '5555555555554444',
        network: 'MS',
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
        window.location.hash = '#/login';
    }
});
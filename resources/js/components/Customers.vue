<template>
    <div id="customers" class="row">
        <div class="col-lg-3">
            <!-- Sidebar -->
            <CustomersFilter
                    v-bind:filter="filter"
                    v-on:filter-customers-clicked="fetchCustomers">
            </CustomersFilter>
        </div>
        <div class="col-lg-9">
            <div class="row" v-if="customers == null">
                <div class="col-md-12 text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-primary mr-2" role="status"></div>
                        <p class="mb-0 lead">Megrendelők betöltése folyamatban...</p>
                    </div>
                </div>
            </div>
            <div class="row" v-else-if="customers.length > 0">
                <div class="col-md-12">
                    <div class="card card-body shadow-sm border-0 p-0">
                        <h5 class="font-weight-bold p-3 mb-0">Találatok</h5>
                        <div class="customer py-2 px-4 action-hover-only"
                             v-for="customer in customers"
                             v-on:click="showCustomer(customer)">
                            <div class="row no-gutters">
                                <!-- Ikonka -->
                                <div class="col-md-auto pr-3">
                                    <div class="d-flex justify-content-center align-items-center bg-info-pastel rounded-circle mt-2"
                                         style="width: 32px; height: 32px;"
                                         v-if="customer['is_reseller'] === 0">
                                    <span class="icon text-info-pastel">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center bg-success-pastel rounded-circle mt-2"
                                         style="width: 32px; height: 32px;"
                                         v-else>
                                    <span class="icon text-success-pastel">
                                        <i class="fas fa-comments-dollar"></i>
                                    </span>
                                    </div>
                                </div>

                                <!-- Név és Lakcím -->
                                <div class="col">
                                    <div class="customer-basics">
                                        <p class="mb-0 font-weight-bold">
                                            <span>{{ customer['name'] }}</span>
                                            <small class="d-block">{{ formatAddress(customer.address) }}</small>
                                        </p>
                                    </div>
                                </div>

                                <!-- Telefonszám és E-mail -->
                                <div class="col-md-3 text-right">
                                    <p class="mb-0 mr-4">
                                        <span>{{ customer['phone'] }}</span>
                                        <small class="d-block text-muted">{{ customer['email'] }}</small>
                                    </p>
                                </div>

                                <!-- Gombok -->
                                <div class="col-md-auto text-right td-action">
                                    <!-- Szerkesztés -->
                                    <a :href="'/megrendelok/' + customer.id + '/szerkesztes'"
                                       class="btn btn-edit-customer btn-sm btn-muted px-1 py-0">
                                        <span class="icon icon-sm">
                                            <i class="fas fa-pen"></i>
                                        </span>
                                    </a>
                                    <!-- Törlés -->
                                    <button class="btn btn-del-customer btn-sm btn-muted px-1 py-0"
                                            v-on:click.stop="deleteCustomer(customer)">
                                        <span class="icon icon-sm">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-12">
                    <p class="mb-4">A szűrés alapján nem található megrendelő az adatbázisban!</p>
                    <a href="/megrendelok/uj" class="btn btn-sm btn-teal">Új megrendelő hozzáadása</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomersFilter from "./CustomersFilter";

    export default {
        components: {CustomersFilter},
        data() {
            return {
                filter: this.initialFilter,
                customers: null,
            }
        },
        props: ['initial-filter'],
        mounted() {
            this.fetchCustomers();
        },
        methods: {
            fetchCustomers() {
                // Szűrés
                let qs = this.getQueryString();

                // Ügyfelek
                this.customers = null;
                fetch('/api/megrendelok' + qs).then((response) => response.json()).then((response) => {
                    this.customers = response.data;
                });

                // URL felülírása
                if (qs !== '') {
                    window.history.pushState('', '', qs);
                } else {
                    window.history.pushState('', '', './');
                }
            },
            showCustomer(customer) {
                window.location.href = "/megrendelok/" + customer.id;
            },
            deleteCustomer(customer) {
                if (confirm('Biztosan törölni szeretnéd a felhasználót? Ez a folyamat nem visszafordítható!')) {

                    this.customers = null;
                    fetch('/api/megrendelok/' + customer.id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then((response) => response.json()).then((response) => {
                        if (response.success === false) {
                            alert('Hiba történt a felhasználó törlésekor!' + response.message);
                        }

                        // Frissítsük a state-t
                        this.fetchCustomers();
                    });
                }
            },
            formatAddress(address) {
                return `${address.zip} ${address.city}, ${address.street}`;
            },
            getQueryString() {
                let data = {};
                let qs = '?';

                if (this.filter.name) {
                    data['filter-name'] = this.filter.name;
                }

                for (const city of this.filter.cities) {
                    if (city.checked) {
                        if (!data['filter-city']) {
                            data['filter-city'] = [];
                        }

                        data['filter-city'].push(city.name);
                    }
                }

                qs += $.param(data);

                return qs !== '?' ? qs : '';
            }
        }
    }
</script>
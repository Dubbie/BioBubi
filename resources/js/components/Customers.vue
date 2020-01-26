<template>
    <div id="customers">
        <div class="row">
            <div class="col-lg-3">
                <!-- Oldalsáv -->
            </div>
            <div class="col-lg-9">
                <div class="card card-body shadow-sm border-0 p-0">
                    <h5 class="font-weight-bold p-3 mb-0">Találatok</h5>
                    <div class="customer py-2 px-4 action-hover-only"
                         v-for="customer in customers">
                        <div class="row no-gutters">
                            <!-- Ikonka -->
                            <div class="col-md-auto pr-3">
                                <div class="d-flex justify-content-center align-items-center bg-info-pastel rounded-circle mt-2"
                                     style="width: 32px; height: 32px;"
                                        v-if="customer['is_reseller']">
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
                                        <small class="d-block">{{ customer['formattedAddress']}}</small>
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
                                <button class="btn btn-edit-customer btn-sm btn-muted px-1 py-0">
                                    <span class="icon icon-sm">
                                        <i class="fas fa-pen"></i>
                                    </span>
                                </button>
                                <!-- Törlés -->
                                <button class="btn btn-del-customer btn-sm btn-muted px-1 py-0">
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
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
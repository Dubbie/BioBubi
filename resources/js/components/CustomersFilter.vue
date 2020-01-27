<template>
    <div id="customers-filter" class="card card-body border-0 shadow-sm">
        <h5 class="font-weight-bold">Szűrők</h5>
        <!-- Név szűrés -->
        <div class="form-group">
            <label for="filter-name">Név tartalmazza</label>
            <input type="text" name="filter-name" id="filter-name" class="form-control form-control-sm" v-model="filter.name">
        </div>

        <!-- Város szűrés-->
        <div class="form-group">
            <p class="mb-0">Város</p>
            <div class="form-group mb-0" v-for="city in filter.cities">
                <div class="custom-control custom-checkbox">
                    <input :id="'filter-city-' + city.slug" class="custom-control-input filter-city" type="checkbox" :value="city.name" name="filter-city[]" v-model="city.checked">
                    <label :for="'filter-city-' + city.slug" class="custom-control-label">
                        <span>{{ city.name }}</span>
                        <span class="text-muted">{{ city.quantity }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group mb-0">
            <button type="button" class="btn btn-sm btn-teal" v-on:click="$emit('filter-customers-clicked')">Szűrés</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CustomersFilter",
        props: ['filter']
    }
</script>
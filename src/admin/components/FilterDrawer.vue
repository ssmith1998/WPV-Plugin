<template>
  <div :class="filterOpen ? 'filterDrawer activeDrawer' : 'filterDrawer'">
      <h2 class="text-white text-center pt-5">Filters</h2>
      <i class="fas fa-times closeDrawer" @click="onCloseDrawer"></i>
      <div class="filters px-4">
          <div class="filterItem">
            <label for="date" class="text-white">Date Start</label>
            <input v-model="filters.date_start" type="date" id="date" class="form-control">
          </div>
          <div class="filterItem pt-2">
            <label for="dateEnd" class="text-white">Date End</label>
            <input v-model="filters.date_end" type="date" id="dateEnd" class="form-control">
          </div>
          <div class="filterItem pt-2">
            <label for="email" class="text-white">Email</label>
            <input v-model="filters.email" type="text" id="email" class="form-control">
          </div>
          <div class="filterItem pt-2">
            <label for="name" class="text-white">Name</label>
            <input v-model="filters.name" type="text" id="name" class="form-control">
          </div>
          <div class="filterItem pt-2">
            <label for="contact_number" class="text-white">Contact Number</label>
            <input v-model="filters.contact_number" type="number" id="contact_number" class="form-control">
          </div>

          <button @click="submitFilters" class="btn btn-light mt-3 mx-auto d-block w-75">Apply Filters</button>
      </div>
  </div>
</template>

<script>
import moment from 'moment'
export default {
name: 'Filter Drawer',
props: {
    filterOpen: {
        type: Boolean,
    },
    showNew: {
        type: Boolean,
    },
},
data() {
    return {
        filters: {
            date_start: '',
            date_end: '',
            email: '',
            name: '',
            contact_number: '',
        }
    }
},
mounted() {
  // const date = moment().format('yyyy-MM-DD');
  // const endDate = moment().add(2, 'months').format('yyyy-MM-DD');
  // this.filters.date_start = date;
  // this.filters.date_end = endDate;
  this.submitFilters();
},
methods: {
    submitFilters() {
        const filters  = this.filters;
        console.log(filters);
        const params = new URLSearchParams({
        date_start: this.filters.date_start,
        date_end: this.filters.date_end,
        email: this.filters.email,
        name: this.filters.name,
        contact_number: this.filters.contact_number,
        });
        this.$emit('filter', params.toString());
    },
    onCloseDrawer() {
      this.$emit('closeDrawer');
    }
 }
}
</script>

<style>
.filterDrawer{
    height: 100%;
    position: fixed;
    right: 0px;
    top: 0px;
    width: 0px;
    background: rgb(35, 35, 106);
    transition: width 0.7s ;
}
.activeDrawer {
    width: 35%;

}

.closeDrawer {
    color: red;
    position: relative;
    top: -38px;
    left: 400px;
}
</style>
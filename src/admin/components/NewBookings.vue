<template>
<div class="wrapper">
  <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Booking From</th>
      <th scope="col">Booking End</th>
      <th scope="col">Email</th>
      <th scope="col">Name</th>
      <th scope="col">Contact Number</th>
    </tr>
  </thead>
  <tbody v-if="bookings.length > 0">
    <tr v-for="(booking, index) in bookings" :key="index" @click="onRowClick(booking)">
      <th scope="row">{{index + 1}}</th>
      <td>{{onFormatDate(booking.booking_start_date)}}</td>
      <td>{{onFormatDate(booking.booking_end_date)}}</td>
      <td>{{booking.email}}</td>
      <td>{{booking.booking_name}}</td>
      <td>{{booking.contact_number}}</td>
    </tr>
  </tbody>
</table>
<booking-modal :isOpen="modalOpen" :booking="currentBooking" />
</div>
</template>

<script>
export default {
name: 'NewBookings',
props: {
    bookings: {
        type: Array,
        default: [],
    },
},
data(){
    return {
        modalOpen: false,
        currentBooking: {}
    }
},
methods: {
    onRowClick(booking) {
        this.modalOpen = true;
        this.currentBooking = booking;
    },
    onFormatDate(date) {
        const dateToFormat = new Date(date);
        return dateToFormat.getDate() + "/" + (dateToFormat.getMonth() + 1) + "/" + dateToFormat.getFullYear() + " " + dateToFormat.getHours() + ":" + dateToFormat.getMinutes();
    }
},
}
</script>

<style>
tr:hover {
    cursor: pointer;
}
</style>
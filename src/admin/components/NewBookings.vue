<template>
<div class="wrapper">
  <table class="table">
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
    <tr v-for="(booking, index) in bookings" :key="index">
      <th scope="row">{{index + 1}}</th>
      <td>{{booking.booking_start_date}}</td>
      <td>{{booking.booking_end_date}}</td>
      <td>{{booking.email}}</td>
      <td>{{booking.booking_name}}</td>
      <td>{{booking.contact_number}}</td>
    </tr>
  </tbody>
</table>
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
}
</script>

<style>

</style>
<template>
  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(booking, index) in bookings" :key="index">
      <th scope="row">{{index + 1}}</th>
      <td>{{booking.name}}</td>
      <td>{{booking.last}}</td>
      <td>{{booking.handle}}</td>
    </tr>
  </tbody>
</table>
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
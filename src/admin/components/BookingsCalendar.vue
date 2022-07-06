<template>
      <Calendar
      :attributes="dates"
       is-expanded 
       class="pb-3" 
       />
</template>

<script>
export default {
name:'BookingsCalendar',
data() {
    return {
        dates: []
    }
},
props: {
    bookings: {
        type: Array,
    }
},
// computed: {
//     dates(){
//       return this.bookings.map(booking => ({
//         key: `booking.${booking.id}`,
//         highlight: true,
//         highlight: {
//             start: { fillMode: 'outline' },
//             base: { fillMode: 'light' },
//             end: { fillMode: 'outline' },
//         },
//         dates: {start: new Date(booking.booking_start_date), end: new Date(booking.booking_end_date)},
//         customData: booking,
//       }));
//     },
// },

mounted() {
    //   this.$axios.get('bookings/calendar').then((response) => {
    //                 console.log(response);
    //                 this.bookings = response.data;
    //                 console.log(this.dates);
    //         });

    console.log('DATES', this.bookings)

    this.dates = this.bookings.map(booking => ({
        key: `booking.${booking.id}`,
        highlight: true,
        highlight: {
            start: { fillMode: 'outline' },
            base: { fillMode: 'light' },
            end: { fillMode: 'outline' },
        },
        dates: {start: new Date(booking.booking_start_date), end: new Date(booking.booking_end_date)},
        customData: booking,
      }));

      
}
}
</script>

<style>

</style>
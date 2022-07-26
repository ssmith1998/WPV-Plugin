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
      <th scope="row">{{booking.id}}</th>
      <td>{{onFormatDate(booking.booking_start_date)}}</td>
      <td>{{onFormatDate(booking.booking_end_date)}}</td>
      <td>{{booking.email}}</td>
      <td>{{booking.booking_name}}</td>
      <td>{{booking.contact_number}}</td>
    </tr>
  </tbody>
</table>
<pagination v-if="pages" :pages="pages" @pageChange="onPageChange" :currentPage="currentPage" />
<booking-modal :isOpen="modalOpen" :booking="currentBooking" @closeModal="modalOpen = false" @updatedCalendars="onUpdateCalendars" />
</div>
</template>

<script>
import moment from 'moment';
import Pagination from './Pagination.vue';
export default {
  components: { Pagination },
name: 'NewBookings',
props: {
    bookings: {
        type: Array,
        default: [],
    },
    pages: {
        type: Number,
        required: true,
    },
    currentPage: {
        type: Number,
        default: 1,
    },
    filterDrawerOpen: {
        type:Boolean,
        default:false,
    },
},
data(){
    return {
        modalOpen: false,
        filterOpen: this.filterDrawerOpen,
        currentBooking: {},
    }
},
methods: {
    onUpdateCalendars(booking) {
        this.$emit('updateBookingCalendars', booking);
    },
    onPageChange(page) {
        this.$emit('pageChange', page);
    },
    onRowClick(booking) {
        this.modalOpen = true;
        this.currentBooking = booking;
        console.log('booking', booking);
        this.currentBooking.booking_start_date = moment(this.currentBooking.booking_start_date).format('YYYY-MM-DD[T]HH:mm:ss');
        this.currentBooking.booking_end_date = moment(this.currentBooking.booking_end_date).format('YYYY-MM-DD[T]HH:mm:ss');
        if(booking.new === "1"){
            console.log('yes');
            this.$axios.post(`/booking/seen/${booking.id}`, {}).then((response) => {
                console.log(response);
                this.onUpdateNewBooking(response.data);
            });
        }
    },
    onUpdateNewBooking(bookingId) {
        console.log('bookingID', bookingId);
        const index = this.bookings.findIndex((item) => {
           return item.id == bookingId;
        });
        console.log('INDEX', index);
        if(index !== -1) {
            this.bookings[index].new = 0;
            this.$emit('updateNewBookingsCount');
        }
    },
    onFormatDate(date) {
        const dateToFormat = moment(date).toDate();
        return dateToFormat.getDate() + "/" + (dateToFormat.getMonth() + 1) + "/" + dateToFormat.getFullYear() + " " + dateToFormat.getHours() + ":" + dateToFormat.getMinutes();
    },
},
}
</script>

<style>
tr:hover {
    cursor: pointer;
}
</style>
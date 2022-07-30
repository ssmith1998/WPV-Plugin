<template>
  <div class="backDrop" v-show="isOpen">
      <div class="modalWrapper w-100 h-75 d-flex justify-content-center align-items-center">
        <div class="card p-5 w-75">
            <i class="fas fa-times text-danger" @click="onCloseModal"></i>
            <form  class="form">
                <h2 class="text-center py-3">{{`Edit Booking #${booking.id}`}}</h2>
                <div class="row">
                    <div class="col-sm-6 pb-2">
                        <input type="text" class="form-control" v-model="booking.email">
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" v-model="booking.booking_name">
                    </div>
                    <div class="col-sm-12 pb-2">
                        <input type="tel" class="form-control" v-model="booking.contact_number">
                    </div>
                    <div class="col-sm-6">
                        <input type="datetime-local" class="form-control" v-model="booking.booking_start_date">
                    </div>
                    <div class="col-sm-6">
                        <input type="datetime-local" class="form-control" v-model="booking.booking_end_date">
                    </div>
                    <div class="col-sm-12 my-2">
                        <button class="btn btn-primary mx-auto d-block w-75" type="button" :disabled="!isValid" @click="onUpdateBooking">Update Booking</button>
                    </div>
                </div>
            </form>
        </div>
      </div>
  </div>
</template>

<script>
import moment, { isMoment } from 'moment';
import { useBookingStore } from '../stores/bookings';
import { mapState, mapActions } from 'pinia'
export default {
name: 'BookingModal',
props: {
    isOpen: {
        default: false,
        type: Boolean
    },
    booking: {
        default: {},
        type: Object
    }
},
data() {
    return {
        start_date: this.booking.booking_start_date,
        end_date: this.booking.booking_end_date,
    }
},
methods: {
     ...mapActions(useBookingStore, ['updateDisabledBookings', 'updateCalendarBooking']),
    onCloseModal() {
        this.$emit('closeModal');
    },
    onUpdateBooking() {
        let booking = this.booking;
        this.$axios.put('/bookings', booking).then((response) => {
            this.onCloseModal();
             Swal.fire({
            title: 'Booking Updated Succesfully',
            timer: 3000,
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            icon: 'success',
            background: 'green',
            color: '#ffffff'
        })  
            console.log("RESPONSE", response);
            this.onUpdateCalendars(response.data);
        });
    },
    onUpdateCalendars(booking) {
      /**
       * Updating new bookings calendar
       */
     this.updateDisabledBookings(booking);

      /**
       * updating main calendar
       */
      this.updateCalendarBooking(booking);
    },
},
computed: {
    isValid() {
        return this.booking.email && this.booking.booking_start_date && this.booking.booking_end_date && this.booking.booking_name && this.booking.contact_number;
    }
}
}
</script>

<style>
.backDrop {
    background: rgb(0, 0, 0, 0.4);
    position: absolute;
    width: 100vw;
    height: 122vh;
    top: 0px;
    right: 0px;

}
.fa-times{
    font-size: 30px;
    cursor: pointer;
    text-align: right;

}
</style>
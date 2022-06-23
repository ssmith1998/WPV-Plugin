<template>
  <div class="backDrop" v-show="isOpen">
      <div class="modalWrapper w-100 h-100 d-flex justify-content-center align-items-center">
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
                        <input type="phone" class="form-control" v-model="booking.contact_number">
                    </div>
                    <div class="col-sm-6">
                        <input type="datetime-local" class="form-control" v-model="startDate">
                    </div>
                    <div class="col-sm-6">
                        <input type="datetime-local" class="form-control" v-model="endDate">
                    </div>
                </div>
            </form>
        </div>
      </div>
  </div>
</template>

<script>
import moment, { isMoment } from 'moment';
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
methods: {
    onCloseModal() {
        this.$emit('closeModal');
    }
},
computed: {
    startDate() {
        const date = new Date(this.booking.booking_start_date);
        const month = date.getMonth() + 1
        return moment(this.booking.booking_start_date).format('YYYY-MM-DD[T]HH:mm:ss');
        // return date.getFullYear() + '-' + (date.getMonth() < 10 ? '0' + month: date.getMonth() + 1) + '-' + date.getDate() + date.getHours() + ':' + date.getMinutes();
    },
     endDate() {
        const date = new Date(this.booking.booking_end_date);
        const month = date.getMonth() + 1
        return moment(this.booking.booking_end_date).format('YYYY-MM-DD[T]HH:mm:ss');
    }
}
}
</script>

<style>
.backDrop {
    background: rgb(0, 0, 0, 0.4);
    position: absolute;
    width: 100vw;
    height: 100vh;
    top: 0px;
    right: 0px;

}
.fa-times{
    font-size: 30px;
    cursor: pointer;
    text-align: right;

}
</style>
<template>
  <div class="backDrop" v-show="isOpen">
      <div class="modalWrapper w-100 h-100 d-flex justify-content-center align-items-center">
        <div class="card p-5 w-75">
            <h1>{{booking.email}}</h1>
        </div>
      </div>
  </div>
</template>

<script>
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
}
}
</script>

<style>
.backDrop {
    background: rgb(0, 0, 0, 0.4);
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0px;
    right: 0px;
;
}
</style>
<template>
  <div
    ref="notification"
    class="card"
    :style="`background-color:${colour}; display:none;`"
  >
    <p>base</p>
  </div>
</template>

<script>
export default {
  name: "BaseNotification",
  props: {
    colour: {
      type: String,
    },
    timeOut: {
      type: Number,
      default: 5000,
    },
  },
  mounted() {
    this.$refs.notification.style.display = "block";
    setTimeout(() => {
      this.$refs.notification.style.display = "none";
    }, this.timeOut);
  },
};
</script>

<style>
</style>
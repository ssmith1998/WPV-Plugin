<template>
  <div class="wrapper">
    <h1 class="pb-1">Calendar</h1>
    <div class="calendarWrapper py-4">
      <Calendar is-expanded class="pb-3" />
    </div>
    <date-picker v-model="range" is-expanded is-range @click="onGetDate" />
    <button class="btn btn-primary w-75 mt-3 mx-auto d-block">Save</button>
  </div>
</template>

<script>
export default {
  name: "calendar",
  data() {
    return {
      date: new Date(),
      range: {
        start: new Date(),
        end: new Date(),
      },
    };
  },
  mounted() {
    // this.$axios.get('https://api.weebly.com').then((resp) => {
    //     console.log('done');
    // })
  },
  methods: {
    onGetDate() {
      console.log(this.range);
    },
  },
};
</script>

<style>
.wrapper {
  display: flex;
  flex-direction: column;
}
</style>
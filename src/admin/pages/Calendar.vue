<template>
<div class="wrapper">
  <h1>Hello</h1>
    <Calendar is-expanded />
    <date-picker v-model="date" />
</div>
</template>

<script>
export default {
name: 'calendar',
data () {
  return {
    date: new Date(),
  }
},
mounted() {
    // this.$axios.get('https://api.weebly.com').then((resp) => {
    //     console.log('done');
    // })
}
}
</script>

<style>
.wrapper {
  display: flex;
  flex-direction: column;
}
</style>
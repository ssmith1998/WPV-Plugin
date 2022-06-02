<template>
<div class="wrapper">
  <h1>Hello</h1>
  <notify colour="red" />
</div>
</template>

<script>
export default {
name: 'calendar',
mounted() {
    this.$axios.get('https://api.weebly.com').then((resp) => {
        console.log('done');
    })
}
}
</script>

<style>

</style>
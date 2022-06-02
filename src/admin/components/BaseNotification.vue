<template>
  <div class="card" :style="`background-color:${colour}`">
      <p>base</p>
  </div>
</template>

<script>
export default {
  name: "BaseNotification",
  props: ['colour'],
};
</script>

<style>
</style>
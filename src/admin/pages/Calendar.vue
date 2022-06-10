<template>
  <div class="wrapper">
    <div class="tabs">
      <ul class="d-flex w-100 justify-content-around">
        <li data-id="calendar" class="w-50 text-center text-white me-3 tab active" @click="switchTab">Calendar</li>
        <li data-id="booking" class="w-50 text-center text-white tab" @click="switchTab">Add Booking</li>
      </ul>
    </div>
    <div data-id="calendar" class="calendarWrapper py-4 tabContent">
      <h1 class="pb-1">Calendar</h1>
      <Calendar is-expanded class="pb-3" />
    </div>
    <div data-id="booking" class="pickerWrapper d-none tabContent">
      <date-picker v-model="range" is-expanded is-range @click="onGetDate" />
      <button class="btn btn-primary w-75 mt-3 mx-auto d-block">Save</button>
    </div>
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
    switchActiveTab(event) {
      const tabs = document.querySelectorAll('.tab');
      const clickedTab = event.target;

      for (let i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('active');
      }

      clickedTab.classList.add('active');
    },
    switchTab(e) {
      this.switchActiveTab(e);
      const tabId = e.target.getAttribute('data-id');
      const tabs = document.querySelectorAll('.tabContent');
      for (let i = 0; i < tabs.length; i++) {
        const tabContentId = tabs[i].getAttribute('data-id');
        if(tabContentId === tabId) {
          tabs[i].classList.remove('d-none');
        }else{
          tabs[i].classList.add('d-none');
        }
        
      }
      
    }
  },
};
</script>

<style lang="scss">

.wrapper {
  display: flex;
  flex-direction: column;
}

.tabs {
  ul{
    li{
      background-color: rgb(47, 47, 56);
      padding: 10px;
      cursor: pointer;
    }
  }
}

.active {
  background: rgb(35, 35, 106)!important;
}
</style>
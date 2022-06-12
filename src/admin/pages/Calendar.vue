<template>
  <div class="wrapper">
    <div class="tabs">
      <ul class="d-flex w-100 justify-content-around">
        <li
          data-id="calendar"
          class="w-50 text-center text-white me-3 tab active"
          @click="switchTab"
        >
          Calendar
        </li>
        <li
          data-id="booking"
          class="w-50 text-center text-white tab"
          @click="switchTab"
        >
          Add Booking
        </li>
      </ul>
    </div>
    <div data-id="calendar" class="calendarWrapper py-4 tabContent">
      <h1 class="pb-1">Calendar</h1>
      <Calendar is-expanded class="pb-3" />
    </div>
    <div data-id="booking" class="pickerWrapper d-none tabContent">
      <date-picker v-model="range" is-expanded is-range mode="dateTime" />
      <form class="p-4 w-75 mx-auto">
        <div class="row">
          <div class="col-sm-6 pb-2">
            <label for="name">Name <span>*</span></label>
            <input type="text" class="form-control" name="name" id="name" v-model="form.name" />
          </div>
          <div class="col-sm-6 pb-2">
            <label for="email">Email <span>*</span></label>
            <input type="text" class="form-control" name="email" id="email" v-model="form.email" />
          </div>
          <div class="col-sm-12 pb-2">
            <label for="contact_number">Contact Number <span>*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="form.contact_number"
              id="contact_number"
              name="contact_number"
            />
          </div>
          <div class="col-sm-12">
            <label for="notes">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" v-model="form.notes"></textarea>
          </div>
        </div>
      <button class="btn btn-primary w-75 mt-3 mx-auto d-block" :disabled="hasError" @click="onSaveBooking">Add Booking</button>
      </form>
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
      form: {
        name: "",
        email: "",
        contact_number: "",
        notes: "",
      },
    };
  },
  computed: {
    hasError() {
      return !this.form.name || !this.form.email || !this.form.contact_number;
    }
  },
  methods: {
    onSaveBooking() {
     const dateRange = this.range;
     const formData = this.form;
    //  this.$axios.post('')
    },
    switchActiveTab(event) {
      const tabs = document.querySelectorAll(".tab");
      const clickedTab = event.target;

      for (let i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("active");
      }

      clickedTab.classList.add("active");
    },
    switchTab(e) {
      this.switchActiveTab(e);
      const tabId = e.target.getAttribute("data-id");
      const tabs = document.querySelectorAll(".tabContent");
      for (let i = 0; i < tabs.length; i++) {
        const tabContentId = tabs[i].getAttribute("data-id");
        if (tabContentId === tabId) {
          tabs[i].classList.remove("d-none");
        } else {
          tabs[i].classList.add("d-none");
        }
      }
    },
  },
};
</script>

<style lang="scss">
.wrapper {
  display: flex;
  flex-direction: column;
}

.tabs {
  ul {
    li {
      background-color: rgb(47, 47, 56);
      padding: 10px;
      cursor: pointer;
    }
  }
}

.active {
  background: rgb(35, 35, 106) !important;
}
</style>
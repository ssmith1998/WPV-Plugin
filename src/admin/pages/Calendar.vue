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
          data-id="newBookings"
          class="w-50 text-center text-white tab me-3"
          @click="switchTab"
        >
          New Bookings
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
    <div data-id="newBookings" class="newBookings pickerWrapper d-none tabContent">
      <h1 class="pb-1">New Bookings</h1>
      <toggle label="Show New Bookings" @onChange="getBookings" />
      <new-bookings :bookings="bookings" />
    </div>
    <div data-id="booking" class="pickerWrapper d-none tabContent">
      <h1 class="pb-1">Add Booking</h1>
      <date-picker v-model="form.range" is-expanded is-range mode="dateTime" />
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
      <button type="button" class="btn btn-primary w-75 mt-3 mx-auto d-block" :disabled="hasError" @click="onSaveBooking">Add Booking</button>
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
      form: {
        name: "",
        email: "",
        contact_number: "",
        notes: "",
        range: {
        start: new Date(),
        end: new Date(),
      },
      },
      bookings: [],
    };
  },
  computed: {
    hasError() {
      return !this.form.name || !this.form.email || !this.form.contact_number;
    },
  },
  methods: {
    getBookings(checkedValue = null) {
        if(checkedValue !== null) {
            this.$axios.get(`bookings?type=${checkedValue}`).then((response) => {
                    this.bookings = response.data;
                
            });
        }else{
            this.$axios.get(`bookings?type=${true}`).then((response) => {
                    this.bookings = response.data;
            });  
        }
    },
    onSaveBooking() {
     this.form.booking_start = this.form.range.start;
     this.form.booking_end = this.form.range.end;
     console.log(this.form.booking_end);
     this.$axios.post('/bookings', this.form ).then(resp => {
       console.log(resp);
     })
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
  mounted(){
    this.getBookings()
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
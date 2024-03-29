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
          ({{bookingsCount}}) Bookings
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
      <Calendar
      :attributes="calendarBookings"
       is-expanded 
       class="pb-3" 
       />
    </div>
    <div data-id="newBookings" class="newBookings pickerWrapper d-none tabContent">
      <h1 class="pb-1">Bookings</h1>
      <div class="filters d-flex">
        <toggle label="Show New Bookings" @onChange="onShowNew" :shownew="showNew" />
        <button style="background:rgb(35, 35, 106);" @click="filterDrawerOpen = !filterDrawerOpen" class="ms-2 border p-2 border-none text-white"><i class="fas fa-filter"></i></button>
      </div>
      <new-bookings :bookings="bookings" :pages="pages" :currentPage="currentPage" @pageChange="onPageChange" @updateNewBookingsCount="onUpdateBookingsCount" @updateBookingCalendars="onUpdateCalendars" />
      <filter-drawer :filterOpen="filterDrawerOpen" @filter="onFilterBookings" @closeDrawer="filterDrawerOpen = !filterDrawerOpen" />
    </div>
    <div data-id="booking" class="pickerWrapper d-none tabContent">
      <h1 class="pb-1">Add Booking</h1>
      <date-picker 
      :disabled-dates="disabledDates"
      v-model="form.range" 
      is-expanded 
      is-range 
      mode="dateTime"
       />
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
import moment from 'moment';
export default {
  name: "calendar",
  data() {
    return {
      loaded: false,
      per_page: 10,
      date: new Date(),
      pages: 0,
      currentPage: 1,
      showNew: true,
      bookingsCount: 0,
      disabledDates: [],
      calendarBookings: [],
      filterDrawerOpen: false,
      filters: '',
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
    onFilterBookings(filters) {
      this.filters = filters;
      this.getBookings();
    },
    onUpdateBookingsCount() {
      this.bookingsCount = this.bookingsCount - 1;
    },
    onShowNew(checkVal) {
      this.showNew = checkVal;
      this.currentPage = 1;
      this.getBookings();
    },
    onPageChange(page) {
      this.currentPage = page;
      this.getBookings();
    },
    async onGetCalendarBookings() {
      return await this.$axios.get('bookings/calendar');
    },
    getBookings() {
            this.$axios.get(`bookings?per_page=${this.per_page}&page=${this.currentPage}&new=${this.showNew}&${this.filters}`).then((response) => {
                    this.bookings = response.data.bookings;
                    this.pages = response.data.number_of_pages;
                    this.bookingsCount = response.data.newBookings;
                
            });
    },
    onUpdateCalendars(booking) {
      /**
       * Updating new bookings calendar
       */
      console.log(this.disabledDates);
      const disable = {
        id: booking.id,
        start: new Date(booking.booking_start_date), 
        end: new Date(booking.booking_end_date),
      };
      const indexDisabled = this.disabledDates.findIndex((item) => {
        return item.id === booking.id
      })
      if(indexDisabled !== -1) {
        this.disabledDates[indexDisabled] = Object.assign(this.disabledDates[indexDisabled], disable);
      }

      /**
       * updating main calendar
       */
      const calendarBooking = {
        key: booking.id,
        highlight: true,
        highlight: {
            start: { fillMode: 'outline' },
            base: { fillMode: 'light' },
            end: { fillMode: 'outline' },
        },
        dates: {start: new Date(booking.booking_start_date), end: new Date(booking.booking_end_date)},
        customData: booking,
      }
      const indexCalendar = this.calendarBookings.findIndex((item) => {
        return item.key === booking.id
      })
      if(indexCalendar !== -1) {
        this.calendarBookings[indexCalendar] = Object.assign(this.calendarBookings[indexCalendar], calendarBooking);
      }
    },
    onSaveBooking() {
     this.form.booking_start = moment(this.form.range.start).add(1, 'hours').toDate();
     this.form.booking_end = moment(this.form.range.end).add(1, 'hours').toDate();
     console.log(this.form);
     this.$axios.post('/bookings', this.form ).then(resp => {
       this.onUpdateState(resp);
       Swal.fire({
        title: 'Booking Created Succesfully',
        timer: 6000,
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        icon: 'success',
        background: 'green',
        color: '#ffffff'
      })
     })
    },
    onUpdateState(resp) {
      this.bookingsCount = this.bookingsCount + 1;
      const bookingStartDate = moment(resp.data.booking_start_date).add(1,'hours').toDate();
      const bookingEndDate = moment(resp.data.booking_end_date).add(1,'hours').toDate();
       this.disabledDates.push({
        id: resp.data.id,
        start: bookingStartDate, 
        end: bookingEndDate,
      })
      this.bookings.push(resp.data);
      this.form = {}
      this.calendarBookings.push({
        key: resp.data.id,
        highlight: true,
        highlight: {
            start: { fillMode: 'outline' },
            base: { fillMode: 'light' },
            end: { fillMode: 'outline' },
        },
        popover: {
                    label: `${resp.data.booking_name} - ${resp.data.email}`
                },
        dates: {start: new Date(resp.data.booking_start_date), end: new Date(resp.data.booking_end_date)},
        customData: resp.data,
      });
      console.log('calendar', this.calendarBookings);
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
  async mounted(){
    /**
     * Bookings Table
     */
    this.getBookings();
    /**
     * Main Calendar bookings
     */
    const response = await this.onGetCalendarBookings();
    this.calendarBookings = response.data.map(booking => ({
      key: booking.id,
        highlight: true,
        highlight: {
            start: { fillMode: 'outline' },
            base: { fillMode: 'light' },
            end: { fillMode: 'outline' },
        },
        popover: {
            label: `${booking.booking_name} - ${booking.email}`
          },
        dates: {start: new Date(booking.booking_start_date), end: new Date(booking.booking_end_date)},
        customData: booking,
    }));
    this.loaded = true;
    /**
     * Fetching disabled dates
     */
     this.$axios.get('bookings/calendar').then((response) => {
      console.log(response);
      this.disabledDates = response.data.map(booking => ({
        id: booking.id,
        start: new Date(booking.booking_start_date), 
        end: new Date(booking.booking_end_date),
      }));
      console.log('DISABLED', this.disabledDates);
    });
    console.log('Pages',this.pages);
  },
};
</script>

<style lang="scss" scoped>
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
import { defineStore } from 'pinia'


export const useBookingStore = defineStore('booking', {
    state: () => {
      return { 
          bookings: [],
          calendarBookingList: [],
          disabled: []
        }
    },
    actions: {
      bookingList(bookings){
          this.bookings = bookings;
      },
      calendarList(bookings){
        this.calendarBookingList = bookings;
      },
      disabledList(bookings) 
      {
        this.disabled = bookings;

      },
      addBooking(booking) {
        this.bookings.unshift(booking);
      },
      addDisabledBooking(booking) {
        this.disabled.push(booking);
      },
      addCalendarBooking(booking) {
        this.calendarBookingList.push(booking);
      },
      removeBooking(booking) {
        const indexFound = this.bookings.findIndex((bookingItem) => {
            return bookingItem.id === booking.id
        });

        if(indexFound !== -1){
            this.bookings.splice(indexFound, 1);
        }
      },
      updateBooking(booking) {
        const bookingFoundIndex = this.bookings.findIndex((bookingItem) => {
            return bookingItem.id === booking.id
        });

        if(bookingFoundIndex !== -1){
            this.bookings[bookingFoundIndex] = Object.assign(this.bookings[bookingFoundIndex], booking);
        }
      },
      updateCalendarBooking(booking) {
          const newCalenarBooking = {
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
          }
        const bookingFoundIndex = this.calendarBookingList.findIndex((bookingItem) => {
            return bookingItem.customData.id === booking.id
        });

        if(bookingFoundIndex !== -1){
            this.calendarBookingList[bookingFoundIndex] = Object.assign(this.calendarBookingList[bookingFoundIndex], newCalenarBooking);
        }
      },
      updateDisabledBookings(booking) {
        const disabled = {
            id: booking.id,
            start: new Date(booking.booking_start_date), 
            end: new Date(booking.booking_end_date),
        }
      const bookingFoundIndex = this.disabled.findIndex((bookingItem) => {
          return bookingItem.id === booking.id
      });

      if(bookingFoundIndex !== -1){
          this.disabled[bookingFoundIndex] = Object.assign(this.disabled[bookingFoundIndex], disabled);
      }
    }
    },
    getters: {
      approved: (state) => state.calendarBookingList.filter(booking => booking.customData.accepted === '1'),
      calendarBookings: (state) => state.calendarBookingList.filter(booking => booking.customData.accepted === '1'),
      unapproved: (state) => state.bookings.filter(booking => booking.accepted !== '1'),
      all: (state) => state.bookings,
      disabledBookings: (state) => state.disabled,
      bookingCount: (state) => {
          const bookingsNewCount = state.bookings.filter(booking => booking.new === '1');
          return bookingsNewCount.length;
      }

    }
  })
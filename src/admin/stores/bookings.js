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
      }
    },
    getters: {
      approved: (state) => state.bookings.filter(booking => booking.accepted === '1'),
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
import { defineStore } from 'pinia';

export const useMintyTestStore = defineStore('minty-test', {
    state: () => ({
        testUser: 'Candidato/a',
        bookings: [],
    }),

    actions: {
        async getBookings() {
            try {
                const response = await fetch('/api/bookings');

                if (!response.ok) {
                    throw new Error('Failed to fetch bookings');
                }

                const data = await response.json();
                this.bookings = data;
            } catch (err) {
                console.error('Error fetching bookings:', err);
            }
        },
    },
});

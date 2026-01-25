import { defineStore } from 'pinia';

export const useMintyTestStore = defineStore('minty-test', {
    state: () => ({
        testUser: 'Candidato/a',
        bookings: [],
        guests: [],
        loading: false,
        error: null,
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

        async getGuests(bookingId) {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(`/api/bookings/${bookingId}/guests`);
                if (!response.ok) {
                    throw new Error('Failed to fetch guests');
                }
                const data = await response.json();

                if (Array.isArray(data)) {
                    this.guests = data.filter(g => g && g.id);
                } else {
                    this.guests = (data && data.id) ? [data] : [];
                }
                
                return this.guests;
            }
            catch (err) {
                this.error = err?.message ?? 'Error fetching guests';
                this.guests = [];
                return [];
            }
            finally {
                this.loading = false;
            }
        },

        async createGuest(bookingId, payload) {
            if (this.loading) return;
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(`/api/bookings/${bookingId}/guests`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                if (!response.ok) {
                    const body = await response.json().catch(() => ({}));
          
                    if (response.status === 409) {
                        throw new Error(body?.error ?? 'Esta reserva ya tiene huésped');
                    }
                    throw new Error(body?.message ?? body?.error ?? 'Failed to create guest');
                }

                const guest = await response.json();
                await this.getGuests(bookingId);
                return guest;

            } catch (err) {
                console.error('Error creating guest:', err);
                this.error = err?.message ?? 'Error creating guest';
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async updateGuest(bookingId, guestId, payload) {
            if (this.loading) return;
            this.loading = true;
            this.error = null;

            try{
                const res = await fetch(`/api/bookings/${bookingId}/guests/${guestId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
                });

            if (!res.ok) throw new Error('Failed to update guest');

            const updated = await res.json();
            await this.getGuests(bookingId);
            return updated;

            } catch (err) {
                console.error('Error updating guest:', err);
                this.error = err?.message ?? 'Error updating guest';
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async deleteGuest(bookingId, guestId) {
            if (this.loading) return;
            this.loading = true;
            this.error = null;

            try {
                const res = await fetch(`/api/bookings/${bookingId}/guests/${guestId}`, { method: 'DELETE' });
                if (!res.ok) throw new Error('Failed to delete guest');

                this.guests = [];
                await this.getGuests(bookingId);

            } catch (err) {
                console.error('Error deleting guest:', err);
                this.error = err?.message ?? 'Error deleting guest';
                throw err;
            } finally {
                this.loading = false;
            }
        },
         
    },
   
});

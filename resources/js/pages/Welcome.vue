<script setup>
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useMintyTestStore } from '../stores/minty-test';

const store = useMintyTestStore();

const goGuest = (bookingId) => {
  router.visit(`/bookings/${bookingId}/guests`);
};

onMounted(async () => {
  await store.getBookings();
});
</script>

<template>
  <Head title="Minty Test 2026" />

  <div class="flex flex-col items-center justify-center gap-6 py-48 px-6">
    <div class="flex flex-col items-center gap-3 text-center max-w-2xl">
      <img src="/images/minty-logo.png" alt="minty" class="h-48 w-auto" />
    </div>

    <div class="w-full max-w-6xl">
      <div v-if="!store.bookings.length" class="text-sm text-gray-600 text-center">
        No hay reservas para mostrar.
      </div>

      <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="b in store.bookings"
          :key="b.id"
          class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
          <div class="font-semibold">Booking #{{ b.id }}</div>

          <div class="mt-2 text-sm text-gray-700">
            <div><span class="text-gray-500">Chekin:</span> {{ b.checkin_at ?? 'N/D' }}</div>
            <div><span class="text-gray-500">Checkout:</span> {{ b.checkout_at ?? 'N/D' }}</div>
            <div><span class="text-gray-500">Status:</span> {{ b.status ?? 'N/D' }}</div>
          </div>

          <div class="mt-4">
            <button class="rounded border px-3 py-2 text-sm hover:bg-gray-100 cursor-pointer" @click="goGuest(b.id)">
              Gestionar huéspedes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

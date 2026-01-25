<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { reactive, ref, onMounted, computed } from 'vue'
import { useMintyTestStore } from '@/stores/minty-test'

const props = defineProps({
  bookingId: Number,
  booking: Object,
})

const store = useMintyTestStore()

const createForm = reactive({ first_name: '', last_name: '', email: '' })

const editingId = ref(null)
const editForm = reactive({ first_name: '', last_name: '', email: '' })

const hasGuest = computed(() => store.guests.some(g => g && g.id))

onMounted(async () => {
  await store.getGuests(props.bookingId)
})

function startEdit(g) {
  editingId.value = g.id
  editForm.first_name = g.first_name ?? ''
  editForm.last_name = g.last_name ?? ''
  editForm.email = g.email ?? ''
}

function cancelEdit() {
  editingId.value = null
  editForm.first_name = ''
  editForm.last_name = ''
  editForm.email = ''
}

async function submitCreate() {
  if (store.loading) return
  if (hasGuest.value) return
  if (!createForm.first_name.trim()) return

  const payload = {
    first_name: createForm.first_name.trim(),
    last_name: createForm.last_name.trim() || null,
    email: createForm.email.trim() || null,
  }

  await store.createGuest(props.bookingId, payload)

  createForm.first_name = ''
  createForm.last_name = ''
  createForm.email = ''
}

async function submitEdit() {
  if (store.loading) return
  if (!editingId.value) return
  if (!editForm.first_name.trim()) return

  const payload = {
    first_name: editForm.first_name.trim(),
    last_name: editForm.last_name.trim() || null,
    email: editForm.email.trim() || null,
  }

  await store.updateGuest(props.bookingId, editingId.value, payload)
  cancelEdit()
}

async function submitDelete(g) {
  if (store.loading) return
  const ok = window.confirm('¿Eliminar este huésped?')
  if (!ok) return

  await store.deleteGuest(props.bookingId, g.id)
  if (editingId.value === g.id) cancelEdit()
}
</script>

<template>
  <Head title="Huéspedes" />

  <div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Panel de huéspedes</h1>
        <p class="text-sm text-gray-600">Booking #{{ props.bookingId }}</p>
      </div>

      <Link href="/" class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-50">
        Volver
      </Link>
    </div>

    <div v-if="store.error" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
      {{ store.error }}
    </div>

    <!-- Crear -->
    <div class="rounded-xl border bg-white p-4 space-y-3">
      <div class="font-medium">Añadir huésped</div>

      <div v-if="hasGuest" class="text-sm text-gray-600">
        Esta reserva ya tiene un huésped. Para cambiarlo, edita o elimina el actual.
      </div>

      <div class="grid gap-2 sm:grid-cols-3">
        <input class="rounded-lg border px-3 py-2 text-sm" placeholder="Nombre *" v-model="createForm.first_name" :disabled="hasGuest || store.loading" />
        <input class="rounded-lg border px-3 py-2 text-sm" placeholder="Apellidos" v-model="createForm.last_name" :disabled="hasGuest || store.loading" />
        <input class="rounded-lg border px-3 py-2 text-sm" placeholder="Email" v-model="createForm.email" :disabled="hasGuest || store.loading" />
      </div>

      <button
        class="rounded bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700 disabled:opacity-50"
        :disabled="store.loading || hasGuest"
        @click="submitCreate"
      >
        Crear
      </button>
    </div>

    <!-- Listado -->
    <div class="rounded-xl border bg-white">
      <div class="border-b p-4 font-medium">Huéspedes</div>

      <div v-if="store.loading" class="p-4 text-sm text-gray-600">Cargando…</div>

      <ul v-else class="divide-y">
        <li v-if="!store.guests.length" class="p-4 text-sm text-gray-600">
          Sin huéspedes.
        </li>

        <li v-for="g in store.guests" :key="g.id" class="p-4">
          <div v-if="editingId === g.id" class="space-y-2">
            <div class="grid gap-2 sm:grid-cols-3">
              <input class="rounded-lg border px-3 py-2 text-sm" v-model="editForm.first_name" :disabled="store.loading" />
              <input class="rounded-lg border px-3 py-2 text-sm" v-model="editForm.last_name" :disabled="store.loading" />
              <input class="rounded-lg border px-3 py-2 text-sm" v-model="editForm.email" :disabled="store.loading" />
            </div>

            <div class="flex gap-2">
              <button class="rounded bg-black px-3 py-2 text-sm text-white hover:bg-gray-800" :disabled="store.loading" @click="submitEdit">
                Guardar
              </button>
              <button class="rounded border px-3 py-2 text-sm hover:bg-gray-50" :disabled="store.loading" @click="cancelEdit">
                Cancelar
              </button>
            </div>
          </div>

          <div v-else class="flex items-start justify-between gap-3">
            <div class="text-sm">
              <div class="font-medium">{{ g.first_name }} {{ g.last_name }}</div>
              <div class="text-gray-600" v-if="g.email">{{ g.email }}</div>
            </div>

            <div class="flex gap-3">
              <button class="text-sm underline" :disabled="store.loading" @click="startEdit(g)">Editar</button>
              <button class="text-sm text-red-700 underline" :disabled="store.loading" @click="submitDelete(g)">Eliminar</button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

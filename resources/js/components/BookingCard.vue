<script setup>
import { ref, reactive } from 'vue';
import { useMintyTestStore } from '@/stores/minty-test'; 

const props = defineProps({
    booking: Object
});

const store = useMintyTestStore();

const isAddingGuest = ref(false);
const isSubmitting = ref(false);
const editingGuestId = ref(null);

const newGuest = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: ''
});

const errors = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: ''
})

const resetFormState = () => {
    newGuest.first_name = '';
    newGuest.last_name = '';
    newGuest.email = '';
    newGuest.phone_number = '';

    errors.first_name = '';
    errors.last_name = '';
    errors.email = '';
    errors.phone_number = '';
};

const editGuest = (guest) => {
    resetFormState();
    editingGuestId.value = guest.id; 
    newGuest.first_name = guest.first_name;
    newGuest.last_name = guest.last_name;
    newGuest.email = guest.email;
    newGuest.phone_number = guest.phone_number;

    // To show the form
    isAddingGuest.value = true;      
};

const validateForm = () => {
    let valid = true;

    if (!newGuest.first_name) {
        errors.first_name = 'El nombre es obligatorio.';
        valid = false;
    } else {
        errors.first_name = '';
    }

    if (!newGuest.last_name) {
        errors.last_name = 'Los apellidos son obligatorios.';
        valid = false;
    } else {
        errors.last_name = '';
    }


    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!newGuest.email) {
        errors.email = 'El email es obligatorio.';
        valid = false;
    } else if (!emailRegex.test(newGuest.email)) {
        errors.email = 'El email no es válido.';
        valid = false;
    } 

    const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s./0-9]*$/;
    if (newGuest.phone_number && !phoneRegex.test(newGuest.phone_number)) {
        errors.phone_number = 'El teléfono contiene caracteres inválidos.';
        isValid = false;
    } else if (newGuest.phone_number && newGuest.phone_number.length < 6) {
        errors.phone_number = 'El número es demasiado corto.';
        isValid = false;
    }

    return valid;
}

const saveGuest = async () => {

    if (!validateForm()) {
        return;
    }

    isSubmitting.value = true;
    let success = false;

    if (editingGuestId.value){
        const payload = {
            id: editingGuestId.value,
            booking_id: props.booking.id, 
            ...newGuest
        };
    
        success = await store.updateGuest(payload);
    }else {
        const payload = {
            booking_id: props.booking.id, 
            ...newGuest
        };
    
        success = await store.addGuest(payload);
    }


    if (success) {
        newGuest.first_name = '';
        newGuest.last_name = '';
        newGuest.email = '';
        newGuest.phone_number = '';
        isAddingGuest.value = false;
        editingGuestId.value = null;
    } else {
        alert('Error al agregar el huésped.');
    } 
    isSubmitting.value = false;
};

const cancelForm = () => {
    isAddingGuest.value = false;
    editingGuestId.value = null;
    newGuest.first_name = '';
    newGuest.last_name = '';
    newGuest.email = '';
    newGuest.phone_number = '';
}

const openAddForm = () => {
    resetFormState();
    isAddingGuest.value = true;
    editingGuestId.value = null;
};

const removeGuest = async (guestId) => {
    if (confirm('¿Borrar huésped?')) {
        await store.deleteGuest(guestId, props.booking.id);
    }
};
</script>

<template>
    <div class="border border-gray-400 p-4 rounded mb-4 bg-white">
        
        <div class="mb-4 border-b pb-2">
            <h2 class="font-bold text-lg">Reserva #{{ booking.id }}</h2>
            <p>In: {{ booking.checkin_at }} | Out: {{ booking.checkout_at }}</p>
        </div>

        <!-- Lista de Huéspedes -->
        <div class="mb-4">
            <h3 class="font-bold">Huéspedes:</h3>
            
            <ul v-if="booking.guests && booking.guests.length > 0">
                <li v-for="guest in booking.guests" :key="guest.id" class="flex justify-between items-center mb-1">
                    <span>- {{ guest.first_name }} {{ guest.last_name }} ({{ guest.email }})</span>
                
                    <button 
                        @click="editGuest(guest)" 
                        class="text-blue-600 font-bold border px-2 text-xs">
                        Edit
                    </button>

                    <button 
                        @click="removeGuest(guest.id)" 
                        class="text-red-600 font-bold ml-2 border px-2 text-xs">
                        X
                    </button>
                </li>
            </ul>
            <p v-else class="text-gray-500 italic">No hay huéspedes.</p>
        </div>

        <div>
            <button 
                v-if="!isAddingGuest" 
                @click="openAddForm"
                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition">
                + Añadir Huésped
            </button>

            <form v-else @submit.prevent="saveGuest" class="bg-gray-50 p-3 mt-2 rounded border border-gray-200 shadow-sm">
                <div class="flex flex-col gap-3">

                    <div>
                        <input 
                            v-model="newGuest.first_name" 
                            placeholder="Nombre" 
                            class="border p-1 w-full rounded"
                            :class="errors.first_name ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                        >
                        <span v-if="errors.first_name" class="text-red-500 text-xs">{{ errors.first_name }}</span>
                    </div>

                    <div>
                        <input 
                            v-model="newGuest.last_name" 
                            placeholder="Apellidos" 
                            class="border p-1 w-full rounded"
                            :class="errors.last_name ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                        >
                        <span v-if="errors.last_name" class="text-red-500 text-xs">{{ errors.last_name }}</span>
                    </div>

                    <div>
                        <input 
                            v-model="newGuest.email" 
                            type="text" 
                            placeholder="Email" 
                            class="border p-1 w-full rounded"
                            :class="errors.email ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                        >
                        <span v-if="errors.email" class="text-red-500 text-xs">{{ errors.email }}</span>
                    </div>

                    <div>
                        <input 
                            v-model="newGuest.phone_number" 
                            placeholder="Teléfono (Opcional)" 
                            class="border p-1 w-full rounded"
                            :class="errors.phone_number ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                        >
                        <span v-if="errors.phone_number" class="text-red-500 text-xs">{{ errors.phone_number }}</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button 
                        type="submit" 
                        :disabled="isSubmitting" 
                        class="bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white px-3 py-1 rounded transition">
                        {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
                    </button>

                    <button 
                        type="button" 
                        @click="cancelForm" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>
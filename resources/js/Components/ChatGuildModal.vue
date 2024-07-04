<script setup>
import { ref } from 'vue';
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    submit: {
        type: Function,
        required: true,
    },
});

var inputs = ref({ 
    name: '', 
    description: '' 
});

var errors = ref({});

const submit = () => {
    props.submit(inputs.value).then(() => {
        close();
        inputs.value = {
            name: '', 
            description: ''
        };
        errors.value = {};
    }).catch((error) => {
        console.error(error.message);
        errors.value = error.errors;
    })
};

const close = () => {
    emit('close');
};
</script>

<template>
    <div>
        <Modal
            :show="show"
            :max-width="maxWidth"
            :closeable="closeable"
            @close="close"
        >
            <div className="bg-white p-8 w-full mx-auto">
                <h3 className="text-lg font-medium mb-4">Create Guild</h3>
                <form @submit.prevent="submit()">
                    <div className="mb-4">
                        <label htmlFor="name"
                            className="block text-sm font-medium text-gray-700">Name</label>
                        <input v-model="inputs.name" type="text" id="name" name="name"
                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        <span v-if="errors.name" v-for="error in errors.name" className="text-xs text-red-500">{{ error }}</span>
                    </div>
                    <div className="mb-4">
                        <label htmlFor="description"
                            className="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="inputs.description" id="description"
                            name="description" rows="3"
                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        <span v-if="errors.description" v-for="error in errors.description" className="text-xs text-red-500">{{ error }}</span>
                    </div>
                    <div className="flex justify-end">
                        <button @click.prevent="close()"
                            className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 mr-4 rounded">
                            Close
                        </button>
                        <button type="submit"
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

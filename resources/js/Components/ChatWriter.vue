<script setup>
import { ref } from 'vue';

const props = defineProps({
    active: {
        type: Boolean,
        default: false,
    },
    submit: {
        type: Function,
        required: true,
    },
});

var message = ref('');

const submit = () => {
    props.submit(message.value);
    message.value = '';
};

</script>

<template>
    <form action="#" @submit.prevent="() => { submit() }">
        <div class="flex">
            <input v-model="message" type="text" :disabled="!props.active" :class="{'bg-gray-200':!props.active}"
                class="w-full p-2 border border-gray-200 rounded-lg" placeholder="Message" />
            <button type="submit" class="bg-sky-600 text-white p-2 rounded-full ml-2" :disabled="!props.active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
            </button>
        </div>
    </form>
</template>
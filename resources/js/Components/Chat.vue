<script setup>

import { useChatStore } from '@/stores/chat';
import { onMounted, watch, ref } from 'vue';
import Modal from './Modal.vue';
import { usePage } from '@inertiajs/vue3';

const chat = useChatStore()
const page = usePage();

const getGuilds = () => {
    axios.get('/api/guilds')
        .then((response) => {
            chat.setGuilds(response.data);
        });
};

const getChannels = () => {
    axios.get(`/api/guilds/${chat.activeGuild.id}`)
        .then((response) => {
            chat.setChannels(response.data.channels);
        });
};

const activeCreateGuild = ref(false);

const createGuildData = ref({});

const createGuild = (createGuildData) => {
    axios.post('/api/guilds', {
        name: createGuildData.name,
        description: createGuildData.description,
    })
        .then((response) => {
            chat.setGuilds([...chat.guilds, response.data]);
            activeCreateGuild.value = false;
        });
};

const deleteGuild = (guild) => {
    if (confirm('Are you sure you want to delete this guild?')) {
        axios.delete(`/api/guilds/${guild.id}`)
            .then(() => {
                chat.setGuilds(chat.guilds.filter((g) => g.id !== guild.id));
                chat.setActiveChannel(null);
                chat.setActiveGuild(null);
            });
    }
};

const deleteMessage = (message) => {
    if (confirm('Are you sure you want to delete this message?')) {
        axios.delete(`/api/guilds/${chat.activeGuild.id}/channels/${chat.activeChannel.id}/messages/${message.id}`)
            .then(() => {
                chat.setMessages(chat.messages.filter((m) => m.id !== message.id));
            });
    }
};

const activeCreateChannel = ref(false);

const createChannelData = ref({});

const createChannel = (createChannelData) => {
    axios.post(`/api/guilds/${chat.activeGuild.id}/channels`, {
        name: createChannelData.name,
        description: createChannelData.description,
    })
        .then((response) => {
            chat.setChannels([...chat.channels, response.data]);
            activeCreateChannel.value = false;
        });
};

const message = ref('');

const sendMessage = () => {
    axios.post(`/api/guilds/${chat.activeGuild.id}/channels/${chat.activeChannel.id}/messages`, {
        content: message.value,
    })
    .finally(() => {
        message.value = '';
    });
};

watch(() => chat.activeGuild, () => {
    if (chat.activeGuild) {
        getChannels();
    }
});

watch(() => chat.activeChannel, (channel, old) => {
    if (old) {
        window.Echo.leave("channel-" + old.id);
        chat.setMessages([]);
    }

    if (channel) {
        window.Echo.channel("channel-" + channel.id).listen("GotMessage", (event) => {
            chat.setMessages([...chat.messages, event]);
        });

    }
});

onMounted(() => getGuilds());

</script>

<template>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="flex h-96">
            <div class="w-0/12 py-2 bg-slate-300 border-r border-gray-200 overflow-y-scroll no-scrollbar">
                <ul>
                    <li v-for="guild in chat.guilds">
                        <a href="#" @click="chat.setActiveGuild(guild)" class="has-tooltip block p-3">
                            <div
                                class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden rounded-full hover:bg-slate-500 bg-slate-600">
                                <span class="font-medium text-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                </span>
                            </div>
                            <span class="tooltip bg-gray-800 text-white p-2 rounded-lg">
                                {{ guild.name }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <Modal :show="activeCreateGuild" :closeable="true">
                            <div className="bg-white p-8 w-full mx-auto">
                                <h3 className="text-lg font-medium mb-4">Create Server</h3>
                                <form @submit.prevent="createGuild(createGuildData)">
                                    <div className="mb-4">
                                        <label htmlFor="name"
                                            className="block text-sm font-medium text-gray-700">Name</label>
                                        <input v-model="createGuildData.name" type="text" id="name" name="name"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                    </div>
                                    <div className="mb-4">
                                        <label htmlFor="description"
                                            className="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea v-model="createGuildData.description" id="description"
                                            name="description" rows="3"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                    <div className="flex justify-end">
                                        <button @click.prevent="() => { activeCreateGuild = false }"
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
                        <a href="#" @click="() => { createGuildData = ref({}); activeCreateGuild = true }"
                            class="has-tooltip block p-3">
                            <div
                                class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden rounded-full hover:bg-green-600 bg-green-700">
                                <span class="font-medium text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                            </div>
                            <span class="tooltip bg-gray-800 text-white p-2 rounded-lg">
                                Add Guild
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <div :class="{ 'w-3/12': chat.activeGuild }"
                class="w-0 bg-slate-200 border-r border-gray-200 overflow-y-scroll no-scrollbar">
                <div class="border-b border-gray-200 block p-4">
                    <a href="#" @click="chat.setActiveGuild(null)" class="text-slate-400 float-right">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                    <a href="#" @click="deleteGuild(chat.activeGuild)" class="text-red-400 float-right pr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </a>

                    <h3 v-if="chat.activeGuild" class="text-lg text-gray-700">
                        {{ chat.activeGuild.name }}
                    </h3>

                    <h3 v-else class="text-lg text-gray-700">Select guild</h3>

                    <p v-if="chat.activeGuild" class="text-slate-400">{{ chat.activeGuild.owner }}</p>
                </div>
                <ul>
                    <li v-for="channel in chat.channels"
                        class="border-b border-gray-200 block p-4 text-md text-slate-700">
                        <a href="#" @click="chat.setActiveChannel(channel)" class="block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="float-left size-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            {{ channel.name }}
                        </a>
                    </li>
                    <li v-if="chat.activeGuild" class="border-b border-gray-200 block p-4 text-md text-green-900">
                        <Modal :show="activeCreateChannel" :closeable="true">
                            <div className="bg-white p-8 w-full mx-auto">
                                <h3 className="text-lg font-medium mb-4">Create Channel</h3>
                                <form @submit.prevent="createChannel(createChannelData)">
                                    <div className="mb-4">
                                        <label htmlFor="name"
                                            className="block text-sm font-medium text-gray-700">Name</label>
                                        <input v-model="createChannelData.name" type="text" id="name" name="name"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                    </div>
                                    <div className="mb-4">
                                        <label htmlFor="description"
                                            className="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea v-model="createChannelData.description" id="description"
                                            name="description" rows="3"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                    <div className="flex justify-end">
                                        <button @click.prevent="() => { activeCreateChannel = false }"
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
                        <a href="#" @click="() => { createChannelData = ref({}); activeCreateChannel = true }"
                            class="block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="float-left p-2 size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Channel
                        </a>
                    </li>
                </ul>
            </div>

            <div :class="{ 'w-full': !chat.activeGuild }" class="w-9/12 flex flex-col justify-between">
                <div>
                    <div class="border-b w-full border-gray-200 block p-4 text-md text-slate-700 bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="float-left size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <span v-if="chat.activeChannel">{{ chat.activeChannel.name }}</span>
                        <span v-else>Select a channel</span>
                    </div>
                </div>
                <div class=" h-full overflow-y-scroll no-scrollbar" :class="{'bg-gray-200':!chat.activeChannel}">
                    <ul class="w-full block p-4">

                        <li v-for="message in chat.messages" class="block w-full">
                            <div v-if="message" class="my-2 px-4 py-2 text-slate-700 rounded-lg"
                                :class="{
                                    'float-left bg-slate-200': message.user_id != page.props.auth.user.id,
                                    'float-right bg-slate-300': message.user_id == page.props.auth.user.id,
                                }"   
                            >
                                <a href="#" @click="deleteMessage(message)" class="text-red-400 float-right pr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                                <span class="text-slate-700 text-sm font-bold">{{ message.user }}</span><br />
                                <span class="text-slate-700">{{ message.content }}</span><br />
                                <span class="text-slate-500 text-xs">{{ message.created_at }}</span>
                                
                            </div>
                            <div class="clear-both"></div>
                        </li>
                    </ul>
                </div>
                <div class="border-t border-gray-200 block p-4 text-md text-slate-700 bg-slate-100">
                    <form action="#" @submit.prevent="sendMessage()">
                        <div class="flex">
                            <input v-model="message" type="text" :disabled="!chat.activeChannel"
                                class="w-full p-2 border border-gray-200 rounded-lg" placeholder="Message" />
                            <button type="submit" class="bg-blue-600 text-white p-2 rounded-full ml-2" :disabled="!chat.activeChannel">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.tooltip {
    @apply invisible absolute;
}

.has-tooltip:hover .tooltip {
    @apply visible z-50;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
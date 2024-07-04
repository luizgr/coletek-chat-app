<script setup>

import { useChatStore } from '@/Stores/chat';
import { onMounted, watch, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ChatGuildModal from './ChatGuildModal.vue';
import ChatChannelModal from './ChatChannelModal.vue';
import ChatWriter from './ChatWriter.vue';

const chat = useChatStore()
const page = usePage();

var sidebarChannels = ref(false);

var createGuildModal = ref(false);
var createChannelModal = ref(false);

const deleteGuild = (guild) => {
    if (confirm('Are you sure you want to delete this guild?')) {
        chat.deleteGuild(guild);
    }
};

const deleteMessage = (message) => {
    if (confirm('Are you sure you want to delete this message?')) {
        chat.deleteMessage(message);
    }
};

const messageContainer = ref(null);

const scrollToBottom = () => {
    setTimeout(() => {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }, 0);
};

watch(() => chat.activeGuild, () => {
    chat.fetchChannels();
});

watch(() => chat.activeChannel, (channel, old) => {
    if (old) {
        chat.leaveChannel(old);
    }

    if (channel) {
        chat.fetchMessages().then(() => {
            scrollToBottom();
        })
        chat.listenChannel(channel, scrollToBottom);
    }
});

onMounted(() => chat.fetchGuilds());

</script>

<template>
    <div>
        <div class="main-chat flex min-w-96 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <!-- Guilds -->
            <div class="w-auto z-30 py-2 bg-slate-300 overflow-y-scroll no-scrollbar">
                <ul>
                    <li v-for="guild in chat.getGuilds">
                        <a href="#" @click="() => {chat.setActiveGuild(guild); sidebarChannels = true;}" class="has-tooltip block p-3">
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
                            <span class="tooltip bg-gray-800 text-white p-2 rounded-lg min-w-48 shadow-slate-700 shadow-2xl">
                                {{ guild.name }}
                                <span class="text-slate-400 text-sm block">{{ guild.description }}</span>
                            </span>
                        </a>
                    </li>
                    <li> 
                        <a href="#" @click="() => { createGuildModal = true }"
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

            <!-- Channels -->
            <div class="w-0 z-20 bg-slate-200 overflow-y-scroll no-scrollbar drop-shadow-lg" :class="{ 'w-3/12': sidebarChannels }">
                <div class="border-b border-gray-200 block p-4 pb-0 divide-y-2">
                    <a href="#" @click="() => { sidebarChannels = false }" class="text-slate-400 float-right">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                    <a v-if="chat.getActiveGuild.id && chat.getActiveGuild.owner_id == page.props.auth.user.id" href="#" @click="deleteGuild(chat.getActiveGuild)" class="text-red-400 float-right pr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </a>

                    <h3 v-if="chat.getActiveGuild.name" class="text-lg text-gray-700">
                        {{ chat.getActiveGuild.name }}
                    </h3>

                    <h3 v-else class="text-lg text-gray-700">Select guild</h3>

                    <div v-if="chat.getActiveGuild.description" class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-b border-gray-300"></div>
                        </div>
                    </div>

                    <p v-if="chat.getActiveGuild.description" class="text-slate-500 text-sm">
                        {{ chat.getActiveGuild.description }}
                    </p>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-b border-gray-300"></div>
                        </div>
                    </div>
                </div>
                <ul>
                    <li v-for="channel in chat.getChannels"
                        class="border-b border-gray-200 block px-4 py-1 text-md text-slate-700">
                        <a href="#" @click="chat.setActiveChannel(channel)" class="block bg-slate-600 rounded-lg p-2 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="float-left size-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            {{ channel.name }}
                        </a>
                    </li>
                    <li v-if="chat.getActiveGuild.id" class="border-b border-gray-200 block p-4">
                        <a href="#" @click="() => { createChannelModal = true }" class="block">
                            <span class="text-md text-green-700">
                                <span class="float-left mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                                Add Channel
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Chat -->
            <div class="w-9/12 z-10 flex flex-col justify-between" :class="{ 'w-full': !sidebarChannels }">
                <!-- Channel name -->
                <div class="w-full block p-4 bg-slate-100 border-b border-gray-200 text-md text-slate-700 drop-shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="float-left size-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    <span v-if="chat.getActiveChannel.id">
                        {{ chat.getActiveChannel.name }}
                        <span class="text-slate-500 text-sm ml-2">{{ chat.getActiveChannel.description }}</span>
                    </span>
                    <span v-else>Select a channel</span>
                </div>

                <!-- Messages -->
                <div ref="messageContainer" class=" bg-gray-200 h-full overflow-y-scroll" :class="{'bg-gray-300':!chat.getActiveChannel}">
                    <ul class="flex flex-col w-full p-4">
                        <li v-for="message in chat.getMessages" class="block w-full">
                            <div v-if="message" class="my-1.5 px-2 py-1 text-slate-700 rounded-lg min-w-48 drop-shadow-sm"
                                :class="{
                                    'float-left bg-slate-100': message.user_id != page.props.auth.user.id,
                                    'float-right bg-slate-200': message.user_id == page.props.auth.user.id,
                                }"   
                            >
                                <span class="text-cyan-800 text-xs font-bold">{{ message.user }}</span><br />
                                <span class="text-slate-700 text-sm">{{ message.content }}</span><br />
                                <span class="text-slate-400 text-xs">{{ message.created_at }}</span>
                                
                                <a href="#" @click="deleteMessage(message)" class="text-slate-300 hover:text-slate-400 float-right ml-2"
                                    v-if="message.user_id == page.props.auth.user.id">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Message input -->
                <div class="border-t border-gray-200 block p-4 text-md text-slate-700 bg-slate-100">
                    <ChatWriter :active="Boolean(chat.getActiveChannel.id)" :submit="chat.sendMessage" />
                </div>
            </div>
        </div>

        <!-- Modals -->

        <!-- Create Guild -->
        <ChatGuildModal :show="createGuildModal" @close="createGuildModal = false" :submit="chat.createGuild" />
        
        <!-- Create Channel -->
        <ChatChannelModal :show="createChannelModal" @close="createChannelModal = false" :submit="chat.createChannel" />

    </div>
</template>

<style scoped>
.main-chat {
    height: 70vh;
}
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
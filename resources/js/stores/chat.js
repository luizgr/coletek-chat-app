import { defineStore } from 'pinia'

export const useChatStore = defineStore('chat', {
    state: () => ({
        guilds: [],
		activeGuild: null,
		channels: [],
		activeChannel: null,
		messages: []
    }),
    getters: {
		getGuilds() {
			return this.guilds
		},
		getActiveGuild() {
			return this.activeGuild
		},
		getChannels() {
			return this.channels
		},
		getActiveChannel() {
			return this.activeChannel
		},
		getMessages() {
			return this.messages
		}
    },
    actions: {
        setGuilds(guilds) {
            this.guilds = guilds
        },
		setActiveGuild(guild) {
			this.activeGuild = guild
		},
		setChannels(channels) {
			this.channels = channels
		},
		setActiveChannel(channel) {
			this.activeChannel = channel
		},
		setMessages(messages) {
			this.messages = messages
		}
    },
})
import { defineStore } from 'pinia'

export const useChatStore = defineStore('chat', {
    state: () => ({
        guilds: [],
		activeGuild: {},
		channels: [],
		activeChannel: {},
		messages: [],
    }),
    getters: {
		getGuilds(state) {
			return state.guilds
		},
		getActiveGuild(state) {
			return state.activeGuild
		},
		getChannels(state) {
			return state.channels
		},
		getActiveChannel(state) {
			return state.activeChannel
		},
		getMessages(state) {
			return state.messages
		}
    },
    actions: {
		/* Setters */
		setActiveGuild(guild) {
			this.activeGuild = guild
		},
		setActiveChannel(channel) {
			this.activeChannel = channel
		},
		setMessages(messages) {
			this.messages = messages
		},
		/* Fetches */
		fetchGuilds() {
			return new Promise((resolve, reject) => {
				axios.get('/api/guilds')
				.then(response => {
					this.guilds = response.data;
					resolve();
				})
				.catch(error => {
					reject(error.message);
				});
			});
		},
		fetchChannels() {
			this.channels = [];
			return new Promise((resolve, reject) => {
				if (this.activeGuild.id) {
					axios.get(`/api/guilds/${this.activeGuild.id}`)
					.then((response) => {
						this.channels = response.data.channels;
						resolve();
					})
					.catch((error) => {
						reject(error.message);
					});
				}
			});
		},
		fetchMessages() {
			this.messages = [];
			return new Promise((resolve, reject) => {
				if (this.activeChannel.id) {
					axios.get(`/api/guilds/${this.activeGuild.id}/channels/${this.activeChannel.id}`)
					.then((response) => {
						this.messages = response.data.latest_messages.reverse();
						resolve();
					})
					.catch((error) => {
						reject(error.message);
					});
				}
			});
		},
		/* Actions */
		createGuild(data) {
			return new Promise((resolve, reject) => {
				axios.post('/api/guilds', {
					name: data.name,
					description: data.description,
				}).then((response) => {
					this.guilds = [...this.guilds, response.data];
					this.activeGuild = response.data;
					this.createGuildModal = false;
					resolve(response.data);
				}).catch((error) => {
					reject(error.response.data);
				});
			});
		},
		deleteGuild(guild) {
			return new Promise((resolve, reject) => {
				axios.delete(`/api/guilds/${guild.id}`)
				.then(() => {
					this.guilds = this.guilds.filter((g) => g.id !== guild.id);
					this.activeGuild = {};
					this.activeChannel = {};
					resolve();
				})
				.catch((error) => reject(error.message));
			});
		},
		createChannel(data) {
			return new Promise((resolve, reject) => {
				axios.post(`/api/guilds/${this.activeGuild.id}/channels`, {
					name: data.name,
					description: data.description,
				}).then((response) => {
					this.channels = [...this.channels, response.data];
					this.activeChannel = response.data;
					this.createChannelModal = false;
					resolve(response.data);
				}).catch((error) => {
					reject(error.response.data);
				});
			});
		},
		sendMessage(content) {
			return new Promise((resolve, reject) => {
				axios.post(`/api/guilds/${this.activeGuild.id}/channels/${this.activeChannel.id}/messages`, {
					content: content,
				})
				.then(response => resolve(response.data))
				.catch(error => reject(error.message));
			});
		},
		deleteMessage(message) {
			return new Promise((resolve, reject) => {
				axios.delete(`/api/guilds/${this.activeGuild.id}/channels/${this.activeChannel.id}/messages/${message.id}`)
				.then(response => resolve(response.data))
				.catch(error => reject(error.message));;
			});
		},
		/* Websockets */
		listenChannel(channel, scrollToBottom) {
			window.Echo.channel("channel-" + channel.id)
			.listen("GotMessage", (event) => {
				this.messages = [...this.messages, event];
				scrollToBottom();
			})
			.listen("DeletedMessage", (event) => {
				this.messages = this.messages.filter((m) => m.id !== event.id);
			});
		},
		leaveChannel(channel) {
			window.Echo.leave("channel-" + channel.id);
			this.messages = [];
		},
    },
})
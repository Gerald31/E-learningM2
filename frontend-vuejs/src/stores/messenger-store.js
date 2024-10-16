import { defineStore, storeToRefs } from "pinia";

export const useMessengerStore = defineStore("messenger", {
    state: () => ({
        status: 'idle',
        delayLoading: true,
        contacts: [],
        conversations: [],
        activeTutoratId: null,
        activeTutorat: null,
        typingIndicator: [],
        openVideo: false,
        openContact: true,
    }),
    getters: {
        contactGroups: (state) => (userId) => {
            let sortedContacts = [...state.contacts.filter((users) => users.id !== userId)];

            sortedContacts.sort();

            let groups = [];
            let currentLetter = '';
            let groupNames = [];

            // create an array of letter for every different sort level.
            for (let contact of sortedContacts) {
                // if the first letter is different create a new group.
                if (contact.firstname[0].toUpperCase() !== currentLetter) {
                    currentLetter = contact.firstname[0].toUpperCase();
                    groupNames.push(currentLetter)
                }
            }

            // create an array that groups contact names based on the first letter;
            for (let groupName of groupNames) {
                let group = { letter: groupName, contacts: [] };
                for (let contact of sortedContacts) {
                    if (contact.firstname[0].toUpperCase() === groupName) {
                        group.contacts.push(contact);
                    }
                }
                groups.push(group);
            }
            return groups;
        },
        filteredConversations: (state) => () => {
            const conversations = state.conversations;
            conversations.messages = conversations.messages.map((message) => {
                if (message.attachment && typeof message.attachment === 'string') {
                    return {
                        ...message,
                        attachment: JSON.parse(message.attachment)
                    }
                }
                return message;
            }).sort((a, b) => Date.parse(a.created_at) - Date.parse(b.created_at));
            return conversations;
        }
    },
    actions: {
        setStatus (payload) {
            this.status = payload;
        },
        setDelayLoading (payload) {
            this.delayLoading = payload;
        },
        setContacts (payload) {
            this.contacts = payload;
        },
        setConversations (payload) {
            this.conversations = payload;
        },
        setActiveStatus (userId, status) {
            this.contacts = this.contacts.map(user => (user.id === userId ? { ...user, active_status: status } : user));
        },
        setActiveVideo () {
            this.openVideo = !this.openVideo;
        },
        setOpenCloseContacts (openContact) {
            this.openContact = openContact;
        },
        updateMessages (payload) {
            this.conversations?.messages?.push({...payload, attachment: JSON.parse(payload.attachment)});
        },
        addTypingIndicator (payload) {
            const userTyping = this.contacts.find(user => user.id === payload);
            this.typingIndicator.push(userTyping);
        },
        removeTypingIndicator (payload) {
            this.typingIndicator = this.typingIndicator.filter(user => user.id !== payload);
        }
    }
});

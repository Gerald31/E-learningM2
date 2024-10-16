export default class messengerApi {
    constructor(axios) {
        this.axios = axios;
    }

    fetchContacts = (tutoratId) => {
        return this.axios.get(`fetchContacts/${tutoratId}`);
    };

    fetchMessages = (tutoratId) => {
        return this.axios.post(`fetchMessages/${tutoratId}`);
    };

    sendMessage = (tutoratId, messages) => {
        return this.axios.post(`sendMessage/${tutoratId}`, messages);
    };

    download = (messageId) => {
        return this.axios.get(`download/${messageId}`, {
            responseType: 'blob',
        });
    };

    setActiveStatus = (params) => {
        return this.axios.post('setActiveStatus', params);
    };
}

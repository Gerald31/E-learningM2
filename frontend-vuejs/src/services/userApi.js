export default class userApi {
    constructor(axios) {
        this.axios = axios;
    }

    fetch = (role) => {
        return this.axios.get(`users/by-role/${role}`);
    };
}

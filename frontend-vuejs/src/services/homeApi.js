export default class homeApi {
    constructor(axios) {
        this.axios = axios;
    }

    fetchHomeStats = () => {
        return this.axios.get('home/statistics');
    };
}

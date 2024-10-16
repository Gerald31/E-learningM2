export default class dashboardApi {
    constructor(axios) {
        this.axios = axios;
    }

    fetchStats = () => {
        return this.axios.get('dashboard/statistics');
    };
}

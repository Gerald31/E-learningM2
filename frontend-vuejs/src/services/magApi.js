export default class magApi {
    constructor(axios) {
        this.axios = axios;
    }

    getAllMags = () => {
        return this.axios.get("mag");
    };

    saveMag = (mag) => {
        return this.axios.post("mag", mag, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        });
    };

    updateStateMag = (magId) => {
        return this.axios.put(`mag/${magId}/update-state`);
    }

}

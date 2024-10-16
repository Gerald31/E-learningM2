export default class classLevelApi {
    constructor(axios) {
        this.axios = axios;
    }

    getAllClassLevel = () => {
        return this.axios.get("class-level");
    };

    saveClassLevel = (classLevel) => {
        return this.axios.post("class-level", classLevel);
    };

    getAllSubject = () => {
        return this.axios.get("subject");
    };

    saveSubject = (subject) => {
        return this.axios.post("subject", subject);
    };

    deleteSubject = (subject) => {
        return this.axios.delete(`subject/${subject.subject_id}`);
    };

    getMyClassLevel = (userId, type) => {
        return this.axios.get(`my-skill/${userId}/${type}`);
    }
}

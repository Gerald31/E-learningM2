export default class tutoratApi {
    constructor(axios) {
        this.axios = axios;
    }

    save = (tutorat) => {
        return this.axios.post("tutorat", tutorat);
    };

    fetch = () => {
        return this.axios.get("tutorat");
    };

    update = (id, tutorat) => {
        return this.axios.put(`tutorat/${id}`, tutorat);
    }

    affectedStudentInTutorat = (id) => {
        return this.axios.put(`tutorat/${id}/affected-student`);
    }

    affectedTutorInTutorat = (id, tutorId) => {
        return this.axios.put(`tutorat/${id}/affected-tutor/${tutorId}`);
    }

    updateTutoratState = (data) => {
        return this.axios.put('tutorat/update/state', data);
    }

    delete = (subject) => {
        return this.axios.delete(`tutorat/${subject.subject_id}`);
    };

    fetchMyTutorat = (id, type) => {
        return this.axios.get(`tutorat/my-tutorat/${id}/${type}`);
    };

    fetchTutoratById = (tutoratId) => {
        return this.axios.get(`tutorat/${tutoratId}`);
    }
}

import { defineStore } from "pinia";
import { fullPathPicture } from '@/utility/media';

const usersStorageVersion = 1;
const usersStorageItemKey = `users_store_${usersStorageVersion}`;

const defaultStoredUsers = {
    tutors: [],
    students: [],
};

const serialiseUsersStore = (state) => {
    sessionStorage.setItem(usersStorageItemKey, JSON.stringify({
        tutors: state.tutors,
        students: state.students,
    }));
}

const deserialiseUsersStore = () => {
    const storedUsers = sessionStorage.getItem(usersStorageItemKey);
    return storedUsers ? JSON.parse(storedUsers) : defaultStoredUsers;
}

const usersStore = deserialiseUsersStore();

export const useUserStore = defineStore("users", {
    state: () => ({
        tutors: usersStore.tutors,
        students: usersStore.students,
    }),
    getters: {
        allTutors: (state) => {
            return state.tutors.map(tutor => {
                return {
                    ...tutor,
                    avatar: fullPathPicture(tutor.avatar)
                }
            });
        },
        allStudents: (state) => {
            return state.students.map(student => {
                return {
                    ...student,
                    avatar: fullPathPicture(student.avatar)
                }
            });
        },
    },
    actions: {
        setTutors (payload) {
            this.tutors = payload;
            serialiseUsersStore(this);
        },
        setStudents (payload) {
            this.students = payload;
            serialiseUsersStore(this);
        },
    },
});

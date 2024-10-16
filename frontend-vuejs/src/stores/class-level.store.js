import { defineStore } from "pinia";
import { getNiveauLabel } from '@/utility/niveau';

const classLevelsStorageVersion = 1;
const classLevelsStorageItemKey = `class_levels_store_${classLevelsStorageVersion}`;

const defaultStoredClassLevel = {
    classLevels: [],
    subjects: [],
    classLevelSelected: null,
    myClassLevels: [],
    mySubjects: [],
};
const serialiseClassLevelStore = (state) => {
    sessionStorage.setItem(classLevelsStorageItemKey, JSON.stringify({
        classLevels: state.classLevels,
        subjects: state.subjects,
        classLevelSelected: state.classLevelSelected,
        myClassLevels: state.myClassLevels,
        mySubjects: state.mySubjects,
    }));
}

const deserialiseClassLevelStore = () => {
    const storedClassLevels = sessionStorage.getItem(classLevelsStorageItemKey);
    return storedClassLevels ? JSON.parse(storedClassLevels) : defaultStoredClassLevel;
}

const classLevelsStore = deserialiseClassLevelStore();

export const useClassLevelStore = defineStore("class_level", {
    state: () => ({
        classLevels: classLevelsStore.classLevels,
        subjects: classLevelsStore.subjects,
        classLevelSelected: classLevelsStore.classLevelSelected,
        myClassLevels: classLevelsStore.myClassLevels,
        mySubjects: classLevelsStore.mySubjects,
    }),
    getters: {
        getclassLevels(state) {
            return state.classLevels.map(classLevel => {
                return {
                    ...classLevel,
                    niveauLabel: getNiveauLabel(classLevel.niveau)
                }
            });
        },
        getMyClassLevels(state) {
            let lastClassLevels = [];
            state.myClassLevels.map(classLevel => {
                if (lastClassLevels.findIndex(lastClassLevel => lastClassLevel.class_level_id === classLevel.class_level_id) === -1) {
                    lastClassLevels.push({
                        ...classLevel,
                        niveauLabel: getNiveauLabel(classLevel.niveau)
                    });
                }
            });
            return lastClassLevels;
        },
        getSubjectsByClassLevel(state){
            return state.subjects.filter(subject => subject.class_level_id === state.classLevelSelected?.class_level_id);
        },
        findSubjectById(state) {
            return (subjectId) => state.mySubjects.find(mySubject => mySubject.subject_id === parseInt(subjectId));
        },
        findClassLevelById(state) {
            return (classLevelId) => state.myClassLevels.find(myClassLevel => myClassLevel.class_level_id === parseInt(classLevelId));
        }
    },
    actions: {
        setClassLevels(payload) {
            this.classLevels = payload;
            serialiseClassLevelStore(this);
        },
        addClassLevel(payload) {
            this.classLevels.push(payload);
            serialiseClassLevelStore(this);
        },
        setSubjects(payload) {
            this.subjects = payload;
            serialiseClassLevelStore(this);
        },
        addSubject(payload) {
            this.subjects.push(payload);
            serialiseClassLevelStore(this);
        },
        setClassLevelSelected(classLevel) {
            this.classLevelSelected = classLevel;
            serialiseClassLevelStore(this);
        },
        getClassLevelsByNiveau(niveauId) {
            return this.classLevels.filter(classLevel => classLevel.niveau === niveauId);
        },
        deleteSubject(subjectDeleted) {
            this.subjects = this.subjects.filter(subject => subject.subject_id !== subjectDeleted.subject_id)
            serialiseClassLevelStore(this);
        },
        setMyClassLevels(payload) {
            this.myClassLevels = payload;
            serialiseClassLevelStore(this);
        },
        setMySubjects(payload) {
            this.mySubjects = payload;
            serialiseClassLevelStore(this);
        },
        getMySubjectClassLevel(classLevelId) {
            return this.mySubjects.filter((subject) => subject.class_level_id === parseInt(classLevelId));
        },
        getSubjectClassLevel(classLevelId) {
            return this.subjects.filter((subject) => subject.class_level_id === parseInt(classLevelId));
        },
    },
});

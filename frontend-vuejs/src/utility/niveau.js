export const NIVEAU_COLLEGE = 1;
export const NIVEAU_COLLEGE_LABEL = "Collège";
export const NIVEAU_LYCEE = 2;
export const NIVEAU_LYCEE_LABEL = "Lycée";
export const NIVEAU_UNIVERSITE = 3;
export const NIVEAU_UNIVERSITE_LABEL = "Universitaire";

export const NIVEAU = [
    {
        id: NIVEAU_COLLEGE,
        label: NIVEAU_COLLEGE_LABEL
    },
    {
        id: NIVEAU_LYCEE,
        label: NIVEAU_LYCEE_LABEL
    },
    {
        id: NIVEAU_UNIVERSITE,
        label: NIVEAU_UNIVERSITE_LABEL
    }
];

export const getNiveauLabel = (niveauId) => {
    let label = null;
    const findNiveau = NIVEAU.find(niveau => niveau.id === niveauId);
    if (findNiveau)
        label =  findNiveau.label;
    return label;
}

import moment from "moment";

export const datefr = (date, letter = false) => {
    if (letter) {
        return moment(date).format("DD MMM YYYY")
    }
    return moment(date).format("DD/MM/YYYY");
}

export const timefr = (date) => {
    return moment(date, "h:m:s").format("HH:mm");
}

export const timefrByFullDate = (date) => {
    return moment(Date.parse(date)).format('HH:mm');
}

export const totalAmount = (amount) => {
    return (parseFloat(amount) + parseFloat(import.meta.env.VITE_PLATFORM_AMOUNT));
}

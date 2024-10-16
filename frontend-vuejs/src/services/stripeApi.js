export default class stripeApi {
    constructor(axios) {
        this.axios = axios;
    }

    createCustomer = (data) => {
        return this.axios.post("stripe/create-customer", data);
    };

    createPaymentIntent = (tutoratId, data) => {
        return this.axios.post(`stripe/create-payment/${tutoratId}`, data);
    };

    paymentIntent = (data) => {
        return this.axios.post("stripe/pay-intent", data);
    };
}

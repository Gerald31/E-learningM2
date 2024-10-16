export default class authApi {
  constructor(axios) {
    this.axios = axios;
  }

  signUpUser = (user) => {
    return this.axios.post("register", user);
  };

  loginUser = (user) => {
    return this.axios.post("login", user);
  };

  verifyEmail = async (verificationCode) => {
    return this.axios.get(`verify/${verificationCode}/user`);
  };

  logoutUser = async () => {
    return this.axios.get("logout");
  };

  getMe = () => {
    return this.axios.get("user");
  };
}

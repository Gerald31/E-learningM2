import axios from "axios";
import { configs } from "@/configs";
import { useAuthStore } from '@/stores';

const instanceAxios = axios.create({
  baseURL: configs.BASE_URL + configs.PREFIX,
});

instanceAxios.interceptors.request.use(
    async (config) => {
      const { getCurrentUser } = useAuthStore();
      const user = await getCurrentUser();
      const isLoggedIn = !!user?.token;
    if (isLoggedIn) {
      config.headers.Authorization = `Bearer ${user?.token}`;
    }
    config.headers["Content-Type"] = "multipart/form-data";
    config.headers["Accept"] = "Application/json";
    config.headers["Access-Control-Allow-Origin"] = "*";
    return config;
  },
  (error) => {
    // Do something with request error
    Promise.reject(error.request.data);
  }
);

instanceAxios.interceptors.response.use(
    (response) => {
        return response;
    },
    async (error) => {
        return Promise.reject(error.response);
    }
);
export default instanceAxios;

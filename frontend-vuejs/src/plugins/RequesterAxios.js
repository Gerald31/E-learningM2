import axios from "@/plugins/axios";
import { configs } from "@/configs";

export default class RequesterAxios {
  async get(url, data) {
    try {
      const response = await axios.get(url, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  }

  async downloadFile(url, params) {
    url = configs.BASE_URL + url + "?";
    for (var param in params) {
      if (Array.isArray(params[param])) {
        if (params[param].length > 0) {
          params[param].forEach((content) => {
            url += param + "[]=" + content + "&";
          });
        }
      } else if (params[param] !== null) {
        url += param + "=" + params[param] + "&";
      }
    }
    window.open(url, "_blank");
  }

  async patch(url, data) {
    try {
      const response = await axios.patch(url, data);

      return response.data;
    } catch (error) {
      throw error;
    }
  }

  async put(url, data) {
    try {
      const response = await axios.put(url, data);

      return response.data;
    } catch (error) {
      throw error;
    }
  }

  async post(url, data) {
    try {
      const response = await axios.post(url, data);

      return response.data;
    } catch (error) {
      throw error;
    }
  }

  async delete(url, data) {
    try {
      const response = await axios.delete(url, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  }
}

import RequesterAxios from "@/plugins/RequesterAxios";
import authApi from "@/services/authApi";
import classLevelApi from "@/services/classLevelApi";
import magApi from '@/services/magApi';
import tutoratApi from '@/services/tutoratApi';
import stripeApi from '@/services/stripeApi';
import messengerApi from '@/services/messengerApi';
import userApi from '@/services/userApi';
import dashboardApi from '@/services/dashboardApi';
import homeApi from '@/services/homeApi';

export default class Api {
  constructor() {
    this.requester = new RequesterAxios();
    this.authApi = new authApi(this.requester);
    this.classLevelApi = new classLevelApi(this.requester);
    this.magApi = new magApi(this.requester);
    this.tutoratApi = new tutoratApi(this.requester);
    this.stripeApi = new stripeApi(this.requester);
    this.messengerApi = new messengerApi(this.requester);
    this.userApi = new userApi(this.requester);
    this.dashboardApi = new dashboardApi(this.requester);
    this.homeApi = new homeApi(this.requester);
  }
}

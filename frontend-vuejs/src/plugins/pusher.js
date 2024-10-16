import Pusher from "pusher-js";
import { configs } from "@/configs";
import { useAuthStore } from "@/stores";

const pusherPlugin = (app) => {
    const { user } = useAuthStore();
    app.config.globalProperties.$pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        authEndpoint: configs.BASE_URL + configs.PREFIX + 'chat/auth',
        auth: {
            headers: {
                'Authorization': `Bearer ${user?.token}`,
                'Access-Control-Allow-Origin': '*',
                'Accept': 'Application/json',
            }
        }
    });
}

export default pusherPlugin;

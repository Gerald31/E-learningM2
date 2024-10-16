import * as filters from "@/utility/filters";

const filtersPlugin = (app) => {
    app.config.globalProperties.$filters = filters;
}

export default filtersPlugin;

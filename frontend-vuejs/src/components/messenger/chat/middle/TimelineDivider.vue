<script setup>
import Typography from '@/components/messenger/reusables/Typography.vue';
import moment from "moment";

const props = defineProps({
    message: Object,
});

const dividerDate = () => {
    return moment(props.message?.created_at).utc().format("DD/MM/YYYY");
}

</script>

<template>
    <div class="w-full my-7 flex items-center justify-center">
        <div class="w-full h-0  border-t border-dashed  dark:border-gray-600 dark:bg-opacity-0"></div>

        <Typography variant="body-4" class="mx-5">
            {{ dividerDate() }}
        </Typography>

        <div class="w-full h-0  border-t border-dashed  dark:border-gray-600 dark:bg-opacity-0"></div>
    </div>
</template>

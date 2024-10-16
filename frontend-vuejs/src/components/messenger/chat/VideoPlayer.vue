<script setup>
import { VideoPlayer } from '@videojs-player/vue';

import 'video.js/dist/video-js.css';
import { onMounted, ref } from 'vue';

const video_player = ref(null);

const props = defineProps({
    url: String,
    thumbnail: String,
    muted: Boolean
});

onMounted(() => {
    console.log('video_player =>', video_player)
})
</script>

<template>
    <div class="basis-[90%] flex justify-center items-center">
        <!--player-->
        <VideoPlayer ref="video_player" class="max-w-[90%] max-h-[90%] flex justify-center items-center" :src="props.url"
            :poster="props.thumbnail" fill responsive controls disablePictureInPicture :muted="muted">
        </VideoPlayer>
    </div>
</template>

<style>
.video-js .vjs-big-play-button {
    top: 50%;
    left: 50%;
}
</style>

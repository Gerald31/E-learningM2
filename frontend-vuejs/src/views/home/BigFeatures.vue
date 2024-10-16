<script setup>
import leftImage from "@/assets/img/left-image.png";
</script>
<template>
    <div class="grid grid-cols-1 gap-6 pb-8 md:flex md:flex-row">
        <div class="self-center basis-1/4 mx-2" data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
            <img :src="leftImage" class="rounded img-fluid d-block mx-auto" alt="App">
        </div>
        <div class="self-center mobile-top-fix basis-2/4 mx-2">
            <div class="left-heading">
                <h2 class="section-title">Comment Learn@Home fonctionne ?</h2>
            </div>
            <div class="left-text">
                <p><strong>Learn@Home</strong> est une plate-forme vous permettant de répondre à des demandes de familles ainsi que de créer vos propres cours, avec une liberté totale.
                    Vous fixez vous-même votre prix ainsi que votre planning!</p>
            </div>
        </div>
        <div class="basis-1/4"></div>
    </div>
    <div class="flex flex-row h-32 min-h-full">
        <div class="grid grid-cols-1 divide-y w-full">
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<style scoped>

</style>

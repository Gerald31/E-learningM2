<script setup>
import rightImage from "@/assets/img/right-image.png";
</script>
<template>
    <div class="gap-6 grid-cols-1 grid md:flex md:flex-row">
        <div class="basis-1/4"></div>
        <div class="self-center mobile-bottom-fix basis-2/4 mx-2">
            <div class="left-heading">
                <h2 class="section-title">Quels sont les différents cours proposés ?</h2>
            </div>
            <div class="left-text">
                <p>Le cours qui assure un échange par visio-conférence avec des fonctionnalités étendues. Toutes les matières et toutes les filières ont leur place sur <strong>Learn@Home</strong>, aucune restriction pour valorisez votre savoir !</p>
            </div>
        </div>
        <div class="self-center mobile-bottom-fix-big basis-1/4 mx-2" data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
            <img :src="rightImage" class="rounded img-fluid d-block mx-auto" alt="App">
        </div>
    </div>
</template>

<style scoped>

</style>

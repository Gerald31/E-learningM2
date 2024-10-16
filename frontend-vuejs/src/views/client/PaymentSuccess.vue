<script setup>
import checkMark from "@/assets/check_mark.png";
</script>

<template>
    <section class="container p-6 mx-auto lg:max-w-6xl">
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="p-2.5 w-full flex-col items-center">
                    <div class="text-center my-12">
                        <img :src="checkMark" class="rounded-full w-32 mb-4 mx-auto" alt="valider">
                    </div>
                    <div class="text-center">
                        Professeur  est affecte a votre tutorat <br>
                        Vous pouvez echanger avec votre tuteur en selectionnant le <br>
                        tutorat dans <strong>"Tutorat en cours"</strong>. <br>
                        Merci pour votre confiance!
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>

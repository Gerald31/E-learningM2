<script setup>
import Button from '@/components/Button.vue';
import router from '@/router';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const interval = 500;

const inter = ref(null);

function generateLocks() {
    const lock = window.document.createElement('div'),
        position = generatePosition();
    lock.innerHTML = '<div class="top"></div><div class="bottom"></div>';
    lock.style.top = position[0];
    lock.style.left = position[1];
    lock.classList.add('lock'); // generated';
    window.document.body.appendChild(lock);
    setTimeout(()=>{
        lock.style.opacity = '1';
        lock.classList.add('generated');
    },100);
    setTimeout(()=>{
        lock.parentElement.removeChild(lock);
    }, 2000);
}
function generatePosition() {
    const x = Math.round((Math.random() * 100) - 10) + '%';
    const y = Math.round(Math.random() * 100) + '%';
    return [x,y];
}

const redirectToHome = async() => {
    await router.push({ name: 'home' });
}

onMounted(() => {
    inter.value = setInterval(generateLocks, interval);
    generateLocks();
})

onBeforeUnmount(() => {
    clearInterval(inter.value)
})

</script>
<template>
    <div
        class="flex flex-col items-center justify-center min-h-screen gap-4 bg-dark-bg"
    >
        <main class="relative flex items-center flex-1 w-full">
            <div
                class="w-full overflow-hidden bg-white shadow-md dark:bg-dark-eval-1"
            >
                <div class="container-404">
                    <h1>4
                        <div class="lock">
                            <div class="top"></div>
                            <div class="bottom"></div>
                        </div>4
                    </h1>
                    <h3 class="text-white font-medium text-2xl -mt-3.5 mb-3.5">Page non trouvée</h3>
                    <p class="text-white">La page que vous avez demandée n'existe pas.</p>
                </div>
            </div>
            <div class="w-full absolute flex justify-center bottom-28">
                <Button @click="redirectToHome">Retour au page d'accueil</Button>
            </div>
        </main>
    </div>
</template>

<style>
/*  403 */
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
    background: #333333;
}
body {
    font-family: sans-serif;
}
.container-404 {
    z-index: 1;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    padding: 10px;
    min-width: 300px;
}
.container-404 div {
    display: inline-block;
}
.container-404 .lock {
    opacity: 1;
    margin-left: -22px;
}
.container-404 h1 {
    font-family: 'Comfortaa', cursive;
    font-size: 100px;
    text-align: center;
    color: #f1e6e6;
    font-weight: 100;
    margin: 0;
}
.container-404 p {
    color: #efe4e4;
}
.lock {
    transition: 0.5s ease;
    position: relative;
    overflow: hidden;
    opacity: 0;
}
.lock.generated {
    transform: scale(0.5);
    position: absolute;
    animation: 2s move linear;
    animation-fill-mode: forwards;
}
.lock ::after {
    content: '';
    background: #a74006;
    opacity: 0.3;
    display: block;
    position: absolute;
    height: 100%;
    width: 50%;
    top: 0;
    left: 0;
}
.lock .bottom {
    background: #d68910;
    height: 40px;
    width: 60px;
    display: block;
    position: relative;
}
.lock .top {
    height: 60px;
    width: 50px;
    border-radius: 50%;
    border: 10px solid #fff;
    display: block;
    position: relative;
    top: 30px;
    left: 5px;
}
.lock .top::after {
    padding: 10px;
    border-radius: 50%;
}

@keyframes move {
    to {
        top: 100%;
    }
}
@media (max-width: 420px) {
    .container-404 {
        transform: translate(-50%, -50%) scale(0.8);
    }
    .lock.generated {
        transform: scale(0.3);
    }
}
</style>

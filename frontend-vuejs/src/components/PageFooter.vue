<template>
    <footer
        class="w-full gap-2 px-6 py-4 lg:flex-row lg:justify-center"
    >
        <div class="w-full">
            <div class="flex flex-wrap mx-4">
                <div class="w-full">
                    <ul class="social overflow-hidden flex items-center justify-center">
                        <li><a href="#"><font-awesome-icon icon="fa-brands fa-facebook" /></a></li>
                        <li><a href="#"><font-awesome-icon icon="fa-brands fa-twitter" /></a></li>
                        <li><a href="#"><font-awesome-icon icon="fa-brands fa-linkedin" /></a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-wrap mx-4">
                <div class="w-full">
                    <p class="copyright">Copyright &copy; {{ new Date().getFullYear() }} Learn@Home , Tous droits reservés</p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { HeartIcon } from "@heroicons/vue/24/solid";
</script>

<style scoped>
footer {
    background-image: linear-gradient(127deg, #a759d1 0%, #8261ee 91%);
    padding-top: 30px;
}

footer .social {
    overflow: hidden;
    margin-top: 10px;
    text-align: center;
}

footer .social li {
    margin: 0px 10px;
    display: inline-block;
}

footer .social li a {
    color: #9d5bd9;
    text-align: center;
    background-color: #fff;
    width: 36px;
    height: 36px;
    line-height: 36px;
    border-radius: 50%;
    display: inline-block;
    font-size: 16px;
    -webkit-transition: all 0.3s ease 0s;
    -moz-transition: all 0.3s ease 0s;
    -o-transition: all 0.3s ease 0s;
    transition: all 0.3s ease 0s;
}

footer .social li a:hover {
    background-color: #ff589e;
    color: #fff;
}

footer .copyright {
    text-align: center;
    border-top: 1px solid rgba(250,250,250,0.2);
    margin-top: 30px;
    padding-top: 30px;
    padding-bottom: 30px;
    font-weight: 400;
    font-size: 12px;
    color: #fff;
    letter-spacing: 0.88px;
    text-transform: uppercase;
}

@media (max-width: 991px) {
    footer .text {
        margin-bottom: 30px;
    }
    footer h5 {
        margin-bottom: 15px;
    }
    footer .footer-nav {
        margin-bottom: 30px;
    }
}
</style>

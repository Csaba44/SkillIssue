<script setup>
import Navbar from "../components/Dashboard/Navbar.vue";
import ProtectedPageContainer from "../components/Generic/ProtectedPageContainer.vue";
</script>

<template>
    <ProtectedPageContainer class="relative overflow-hidden">
        
        <i class="pointer-events-none fa-solid fa-clipboard-question rotate-12 text-accentPurple text-[1500px] absolute z-0 opacity-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
        
        <Navbar minimal />

        <section class="relative z-10 mt-24 max-w-6xl mx-auto px-6 text-textWhite">
            <div class="flex flex-col gap-2">
                <h2 class="text-4xl font-bold italic tracking-tight uppercase">
                    Admin <span class="text-primary not-italic">Kérdések</span>
                </h2>
                <p class="text-white/30 text-sm font-medium tracking-wide">
                    Vezérlőpult a rendszer kérdéseinek kezeléséhez
                </p>
            </div>

            <div class="mt-12">
                </div>
        </section>

    </ProtectedPageContainer>
</template>
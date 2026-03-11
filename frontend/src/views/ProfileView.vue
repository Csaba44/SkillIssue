<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import api from "../config/api";

const route = useRoute();

const profile = ref([]);

const getUser = async () => {
    try {
        const res = await api.get(`/api/profiles/${route.params.id}`);
        if (res.status != 200) {
            return console.log("error", res);
        };
        profile.value = res.data;
        console.log(profile.value);
    } catch (error) {
        console.log(error);
    }
}

onBeforeMount(getUser);
</script>
<template>

</template>
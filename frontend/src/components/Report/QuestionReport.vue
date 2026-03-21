<script setup>
import { ref } from "vue";
import api from "../../config/api";
import { toast } from "vue-sonner";
import Button from "../Generic/Button.vue";

const props = defineProps({
  questionToken: { type: String, required: true },
  question: { type: String, required: true },
  answers: { type: Array, required: true },
});

const formData = ref({ comment: "" });
const isSubmitted = ref(false);

const formSubmitted = async () => {
  if (formData.value.comment.trim() === "") return toast.error("Minden mező kitöltése kötelező.", { duration: 3000 });

  const submitPromise = async () => {
    const res = await api.post("/api/question-reports", {
      question_token: props.questionToken,
      comment: formData.value.comment,
    });
    return res.data;
  };

  toast.promise(submitPromise(), {
    loading: "Bejelentés folyamatban...",
    success: () => {
      isSubmitted.value = true;
      return "Sikeres bejelentés! Köszönjük a hozzájárulását!";
    },
    error: (error) => {
      if (!error.response) return "Ellenőrizd az internetkapcsolatot.";
      const { status, data } = error.response;
      if (status === 403) return "A bejelentett kérdés nem létezik, vagy túl régen játszott meccsről származik.";
      if (status === 401) return data.message;
      if (status !== 422) return "Ismeretlen hiba történt.";
      const errors = data.errors;
      const firstKey = Object.keys(errors)[0];
      if (firstKey === "question_id") return "Ezt a kérdést már bejelentetted.";
      return errors[firstKey][0];
    },
  });
};
</script>
<template>
  <section class="relative z-10 mt-20 max-w-3xl mx-auto px-4 text-textWhite pb-20">
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <i class="fa-solid fa-flag text-error text-xl"></i>
        <h2 class="text-3xl font-bold">Kérdésbejelentő</h2>
      </div>
      <p class="text-white/40 text-sm ml-9">Segíts nekünk javítani a kérdések minőségén!</p>
    </div>

    <div v-if="isSubmitted" class="p-10 rounded-3xl bg-white/5 backdrop-blur-xl border border-accentGreen/20 shadow-xl flex flex-col items-center gap-4 text-center">
      <div class="w-16 h-16 rounded-2xl bg-accentGreen/20 flex items-center justify-center text-accentGreen text-3xl">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <h3 class="text-2xl font-bold">Bejelentés elküldve!</h3>
      <p class="text-white/40 text-sm max-w-sm">Köszönjük a hozzájárulását. Csapatunk hamarosan megvizsgálja a bejelentést.</p>
    </div>

    <template v-else>
      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-5">
        <p class="text-white/40 text-xs uppercase tracking-widest font-semibold mb-3">Bejelentett kérdés</p>
        <h3 class="text-lg font-bold leading-snug">{{ props.question }}</h3>
      </div>

      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-5">
        <p class="text-white/40 text-xs uppercase tracking-widest font-semibold mb-4">Lehetséges válaszok</p>
        <ul class="flex flex-col gap-2">
          <li v-for="answer in answers" :key="answer.id" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5 border border-white/5 text-sm text-white/70">
            <i class="fa-regular fa-circle text-white/20 text-xs"></i>
            {{ answer.answer.replace("*", "") }}
          </li>
        </ul>
      </div>

      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-6">
        <label class="text-white/40 text-xs uppercase tracking-widest font-semibold block mb-3">Leírás</label>
        <div class="flex min-w-30 bg-bgDark border border-white/25 rounded-md">
          <div class="flex items-center justify-center px-3 pt-3 self-start">
            <i class="fa-solid fa-pen text-accentPurple text-xl"></i>
            <span class="bg-accentPurple ml-2 h-6 w-0.5"></span>
          </div>
          <textarea v-model="formData.comment" placeholder="Kérlek fejtsd ki a problémát részletesen!" rows="4" class="text-textWhite w-full outline-none bg-transparent py-3 pr-3 resize-none text-sm placeholder:text-white/20" />
        </div>
      </div>

      <div class="flex justify-end">
        <Button title="Bejelentés küldése" class="bg-gradient-to-r from-error to-red-400 text-white font-bold rounded-full px-10 py-3 shadow-lg shadow-red-500/20 hover:scale-105 transition-all duration-300" @click="formSubmitted" />
      </div>
    </template>
  </section>
</template>

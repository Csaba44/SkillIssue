<script setup>
import { computed, ref } from "vue";
import api from "../../config/api";
import { toast } from "vue-sonner";
import Button from "../Generic/Button.vue";
import Select from "../Generic/Select.vue";
import ReportSent from "./ReportSent.vue";

const props = defineProps({
  rankedToken: { type: String, required: true },
  opponentName: { type: String, required: true },
  reportRound: { type: Number, required: true },
});

const roundOptions = computed(() =>
  Array.from({ length: props.reportRound }, (_, i) => ({
    value: i + 1,
    label: `${i + 1}. kör`,
  })),
);

const formData = ref({ round_number: props.reportRound, comment: "" });
const isSubmitted = ref(false);

const formSubmitted = async () => {
  if (formData.value.comment.trim() === "") return toast.error("Minden mező kitöltése kötelező.", { duration: 3000 });

  const submitPromise = async () => {
    const res = await api.post("/api/user-reports", {
      ranked_token: props.rankedToken,
      round_number: formData.value.round_number,
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
      if (status === 403) return "A bejelentett játékos nem található, vagy túl régen játszott meccsről származik.";
      if (status === 401) return data.message;
      if (status !== 422) return "Ismeretlen hiba történt.";
      const errors = data.errors;
      const firstKey = Object.keys(errors)[0];
      if (firstKey === "game_match_id") return "Ezt a felhasználót már bejelentetted. Dolgozunk rajta.";
      return errors[firstKey][0];
    },
  });
};
</script>

<template>
  <section class="relative z-10 mt-20 max-w-3xl mx-auto px-4 text-textWhite pb-20">
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <i class="fa-solid fa-user-slash text-error text-xl"></i>
        <h2 class="text-3xl font-bold">Játékosbejelentő</h2>
      </div>
      <p class="text-white/40 text-sm ml-9">Segíts nekünk megőrizni a fair play szellemét!</p>
    </div>

    <ReportSent v-if="isSubmitted" />

    <template v-else>
      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-5">
        <p class="text-white/40 text-xs uppercase tracking-widest font-semibold mb-3">Bejelentett játékos</p>
        <h3 class="text-lg font-bold leading-snug">{{ props.opponentName }}</h3>
      </div>

      <div
        class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-5 overflow-visible relative z-20"
      >
        <p class="text-white/40 text-xs uppercase tracking-widest font-semibold mb-4">Érintett kör</p>
        <p class="text-white/40 text-xs mb-3">Ha mindegyik kör gyanús volt, hagyd az alapértelmezett értéken.</p>
        <Select v-model="formData.round_number" :options="roundOptions" />
      </div>

      <div class="p-6 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-xl mb-6">
        <label class="text-white/40 text-xs uppercase tracking-widest font-semibold block mb-3">Leírás</label>
        <div class="flex min-w-30 bg-bgDark border border-white/25 rounded-md">
          <div class="flex items-center justify-center px-3 pt-3 self-start">
            <i class="fa-solid fa-pen text-accentPurple text-xl"></i>
            <span class="bg-accentPurple ml-2 h-6 w-0.5"></span>
          </div>
          <textarea
            v-model="formData.comment"
            placeholder="Kérlek fejtsd ki a problémát részletesen!"
            rows="4"
            class="text-textWhite w-full outline-none bg-transparent py-3 pr-3 resize-none text-sm placeholder:text-white/20"
          />
        </div>
      </div>

      <div class="flex justify-end">
        <Button
          title="Bejelentés küldése"
          class="bg-linear-to-r from-error to-red-400 text-white font-bold rounded-full px-10 py-3 shadow-lg shadow-red-500/20 hover:scale-105 transition-all duration-300"
          @click="formSubmitted"
        />
      </div>
    </template>
  </section>
</template>

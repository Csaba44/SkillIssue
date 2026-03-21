<script setup>
import { ref } from "vue";
import api from "../../config/api";
import { toast } from "vue-sonner";

import Button from "../Generic/Button.vue";

const props = defineProps({
  rankedToken: { type: String, required: true },
  opponentName: { type: String, required: true },
  reportRound: { type: Number, required: true },
});

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
  <h1>Játékosbejelentő űrlap</h1>
  <div>
    <p>Bejelentett játékos neve: {{ props.opponentName }}</p>

    <label for="">Melyik körben történt csalás? (Ha mindegyik kör gyanús volt, hagyd az alapértelmezett értéken.)</label>
    <select name="" id="" v-model="formData.round_number">
      <option :value="roundNumber" v-for="roundNumber in props.reportRound" :key="roundNumber">{{ roundNumber }}</option>
    </select>

    <p>Leírás</p>
    <textarea v-model="formData.comment" name="" id="" placeholder="Kérlek fejtsd ki a problémát részletesen!"></textarea>
    <Button title="Bejelentés küldése" class="bg-gradient-to-r from-error to-red-400 text-white font-bold rounded-full px-10 py-3 shadow-lg shadow-red-500/20 hover:scale-105 transition-all duration-300" @click="formSubmitted" />
  </div>
</template>

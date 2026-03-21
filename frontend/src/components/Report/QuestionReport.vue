<script setup>
import { ref } from "vue";
import api from "../../config/api";
import { toast } from "vue-sonner";

const props = defineProps({
  questionToken: {
    type: String,
    required: true,
  },
  question: {
    type: String,
    required: true,
  },
  answers: {
    type: Array,
    required: true,
  },
});

const formData = ref({
  comment: "",
});

const formSubmitted = async () => {
  if (formData.value.comment.trim() == "") return toast.error("Minden mező kitöltése kötelező.", { duration: 3000 });

  const submitPromise = async () => {
    const res = await api.post("/api/question-reports", {
      question_token: props.questionToken,
      comment: formData.value.comment,
    });

    return res.data;
  };

  toast.promise(submitPromise(), {
    loading: "Bejelentés folyamatban...",
    success: (data) => data.message,
    error: (error) => {
      if (!error.response) return "Ellenőrizd az internetkapcsolatot.";

      const { status, data } = error.response;

      if (status === 401) return data.message;
      if (status !== 422) return "Ismeretlen hiba történt.";

      const errors = data.errors;
      const firstKey = Object.keys(errors)[0];
      return errors[firstKey][0];
    },
  });
};
</script>

<template>
  <h1>Kérdésbejelentő űrlap</h1>
  <form @submit.prevent="formSubmitted">
    <p>Bejelentett kérdés: {{ props.question }}</p>
    <p>Lehetséges válaszok:</p>
    <ul>
      <li v-for="answer in answers" :key="answer.id">{{ answer.answer.replace("*", "") }}</li>
    </ul>

    <label>Leírás: </label>
    <textarea placeholder="Kérlek fejtsd ki a problémát!" v-model="formData.comment"></textarea>

    <button type="submit">Küldés</button>
  </form>
</template>

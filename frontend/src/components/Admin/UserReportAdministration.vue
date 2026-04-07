<script setup>
import { ref, computed } from "vue";
import UserReportItem from "./UserReportItem.vue";
import api from "../../config/api";
import { toast } from "vue-sonner";

const props = defineProps({
  reports: Array,
});
const emit = defineEmits(["refresh", "deleteReport"]);

const searchQuery = ref("");
const selectedReport = ref(null);
const isModalOpen = ref(false);
const isSubmitting = ref(false);

const adminNote = ref("");
const activeStatus = ref("");

const showBanForm = ref(false);
const isBanning = ref(false);
const banForm = ref({
  reason: "",
  release_date: "",
});
const showMatchDetails = ref(false);

const statusTranslations = {
  Open: "Nyitott",
  Investigating: "Vizsgálat",
  Closed: "Lezárva",
};

// Segédfüggvény a lokális idő UTC-be forgatásához a backendnek
const toUTCString = (localDateTime) => {
  if (!localDateTime) return null;
  const date = new Date(localDateTime);
  return date.toISOString().slice(0, 19).replace("T", " ");
};

const filteredReports = computed(() => {
  const order = { Investigating: 1, Open: 2, Closed: 3 };

  return props.reports
    .filter((r) => {
      const search = searchQuery.value.toLowerCase();
      return (
        !search ||
        r.user_reported?.name.toLowerCase().includes(search) ||
        r.user_reporting?.name.toLowerCase().includes(search) ||
        r.comment.toLowerCase().includes(search)
      );
    })
    .sort((a, b) => (order[a.status] || 99) - (order[b.status] || 99));
});

const openDetails = async (report) => {
  showMatchDetails.value = false;
  selectedReport.value = report;
  adminNote.value = "";
  activeStatus.value = report.status;
  isModalOpen.value = true;
  showBanForm.value = false;
  banForm.value = { reason: "", release_date: "" };

  if (report.status == "Open") {
    activeStatus.value = "Investigating";
    await saveUpdate("Investigating");
  }
};

const saveUpdate = async (statusOverride = null) => {
  const statusToSave = statusOverride || activeStatus.value;
  if (!statusOverride) isSubmitting.value = true;

  const finalComment = adminNote.value
    ? `${selectedReport.value.comment} | Admin: ${adminNote.value}`
    : selectedReport.value.comment;

  try {
    await api.put(`/api/user-reports/${selectedReport.value.id}`, {
      status: statusToSave,
      comment: finalComment,
      user_id: selectedReport.value.user_id,
      game_match_id: selectedReport.value.game_match_id,
      round_number: selectedReport.value.round_number,
    });

    if (!statusOverride) {
      toast.success("Játékos jelentés frissítve!");
      isModalOpen.value = false;
    }
    emit("refresh");
  } catch (error) {
    console.error("Hiba:", error.response?.data);
    if (!statusOverride) toast.error("Hiba történt a mentéskor.");
  } finally {
    isSubmitting.value = false;
  }
};

const banUser = async () => {
  if (!banForm.value.reason || !banForm.value.release_date) {
    return toast.error("Kérjük add meg a ban okát és lejárati dátumát.");
  }

  isBanning.value = true;

  // Lokális idő konvertálása UTC-re beküldés előtt
  const utcDate = toUTCString(banForm.value.release_date);

  try {
    await api.post("/api/ban", {
      user_id: selectedReport.value.user_reported?.id,
      release_date: utcDate,
      reason: banForm.value.reason,
    });

    activeStatus.value = "Closed";
    adminNote.value = adminNote.value
      ? adminNote.value + ` | Kitiltva: ${banForm.value.reason}`
      : `Kitiltva: ${banForm.value.reason}`;

    await saveUpdate("Closed");

    toast.success(`${selectedReport.value.user_reported?.name} sikeresen kitiltva.`);
    isModalOpen.value = false;
  } catch (error) {
    console.error("Kitiltás során hiba:", error.response?.data);
    toast.error("Hiba történt a tiltás során.");
  } finally {
    isBanning.value = false;
  }
};
</script>

<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
      <div class="relative w-full lg:w-96">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/20"></i>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Játékos vagy indok keresése..."
          class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-white outline-none focus:border-red-500/50 transition-all"
        />
      </div>
      <div class="text-white/40 text-sm font-medium">
        Összesen: <span class="text-white">{{ reports.length }}</span> bejelentett játékos
      </div>
    </div>

    <div class="grid gap-4">
      <div v-if="filteredReports.length > 0" class="grid gap-3">
        <UserReportItem
          v-for="report in filteredReports"
          :key="report.id"
          :report="report"
          :class="{ 'opacity-50 grayscale': report.status == 'Closed' }"
          @view="openDetails"
          @delete="(id) => emit('deleteReport', id)"
        />
      </div>
      <div v-else class="py-20 text-center border border-dashed border-white/5 rounded-3xl bg-white/1">
        <p class="text-white/20 font-medium">Nincs aktív játékos jelentés.</p>
      </div>
    </div>

    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
    >
      <div
        class="bg-bgDark border border-white/10 w-full max-w-xl rounded-3xl sm:p-8 p-3 shadow-2xl overflow-y-auto max-h-[95vh]"
      >
        <div class="flex justify-between items-start mb-8">
          <div>
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
              <i class="fa-solid fa-user-shield text-amber-500"></i> Játékos jelentés
            </h2>
            <div class="mt-2">
              <span
                class="text-[10px] uppercase font-black px-2 py-0.5 rounded bg-white/5 text-white/40 tracking-widest"
              >
                Állapot: {{ statusTranslations[selectedReport.status] }}
              </span>
            </div>
          </div>
        </div>

        <div class="space-y-6 text-left">
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1"
                >Bejelentett</label
              >
              <p
                class="text-white font-medium cursor-pointer hover:text-accentGreen transition-colors"
                @click="$router.push(`/profiles/${selectedReport.user_reported?.id}`)"
              >
                {{ selectedReport.user_reported?.name }}
              </p>
              <p class="text-white/30 text-xs mt-8 sm:mt-0.5">{{ selectedReport.user_reported?.email }}</p>
            </div>
            <div>
              <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1"
                >Bejelentett forduló</label
              >
              <p class="text-white font-medium">{{ selectedReport.round_number }}. kör</p>
            </div>
            <div>
              <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1"
                >Átlag válaszidő</label
              >
              <p
                v-if="selectedReport.match_details?.length"
                :class="[
                  'font-mono font-bold text-sm',
                  selectedReport.match_details.reduce((s, d) => s + d.user_guess_time_ms, 0) /
                    selectedReport.match_details.length <
                  1000
                    ? 'text-red-500'
                    : 'text-accentGreen',
                ]"
              >
                {{
                  (
                    selectedReport.match_details.reduce((s, d) => s + d.user_guess_time_ms, 0) /
                    selectedReport.match_details.length /
                    1000
                  ).toFixed(2)
                }}s
              </p>
              <p v-else class="text-white/20 italic text-[10px]">Nincs adat</p>
            </div>
          </div>

          <div class="bg-white/3 border border-white/5 rounded-2xl p-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="text-center">
              <p class="text-white/30 text-[10px] uppercase font-bold tracking-widest mb-1">ELO</p>
              <p class="text-white font-bold text-lg">{{ selectedReport.user_reported?.elo ?? "–" }}</p>
            </div>
            <div class="text-center">
              <p class="text-white/30 text-[10px] uppercase font-bold tracking-widest mb-1">XP</p>
              <p class="text-accentYellow font-bold text-lg">{{ selectedReport.user_reported?.xp ?? "–" }}</p>
            </div>
            <div class="text-center">
              <p class="text-white/30 text-[10px] uppercase font-bold tracking-widest mb-1">Meccsek</p>
              <p class="text-white font-bold text-lg">
                {{ selectedReport.user_reported?.played_matches_count ?? "–" }}
              </p>
            </div>
            <div class="text-center">
              <p class="text-white/30 text-[10px] uppercase font-bold tracking-widest mb-1">Win rate</p>
              <p
                class="font-bold text-lg"
                :class="(selectedReport.user_reported?.win_rate ?? 0) >= 50 ? 'text-accentGreen' : 'text-red-400'"
              >
                {{ selectedReport.user_reported?.win_rate != null ? selectedReport.user_reported.win_rate + "%" : "–" }}
              </p>
            </div>
            <div class="text-center col-span-4 border-t border-white/5 pt-3 mt-1">
              <p class="text-white/30 text-[10px] uppercase font-bold tracking-widest mb-1">Helyes válaszok aránya</p>
              <div class="flex items-center justify-center gap-3">
                <div class="flex-1 max-w-xs bg-white/5 rounded-full h-1.5 overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all"
                    :class="
                      (selectedReport.user_reported?.accuracy ?? 0) >= 60
                        ? 'bg-accentGreen'
                        : (selectedReport.user_reported?.accuracy ?? 0) >= 35
                          ? 'bg-accentYellow'
                          : 'bg-red-400'
                    "
                    :style="`width: ${selectedReport.user_reported?.accuracy ?? 0}%`"
                  ></div>
                </div>
                <p
                  class="font-bold text-sm"
                  :class="
                    (selectedReport.user_reported?.accuracy ?? 0) >= 60
                      ? 'text-accentGreen'
                      : (selectedReport.user_reported?.accuracy ?? 0) >= 35
                        ? 'text-accentYellow'
                        : 'text-red-400'
                  "
                >
                  {{
                    selectedReport.user_reported?.accuracy != null
                      ? selectedReport.user_reported.accuracy.toFixed(1) + "%"
                      : "–"
                  }}
                </p>
              </div>
            </div>
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2"
              >Bejelentő indoklása</label
            >
            <div class="text-amber-200/60 bg-amber-500/5 p-4 rounded-xl border border-amber-500/10 italic text-sm">
              "{{ selectedReport.comment }}"
            </div>
          </div>

          <div>
            <label class="text-white/20 text-[10px] uppercase font-bold tracking-widest block mb-2"
              >Admin megjegyzés</label
            >
            <textarea
              v-model="adminNote"
              placeholder="Belső jegyzet a kivizsgálásról..."
              rows="3"
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white text-sm outline-none focus:border-amber-500/40 transition-all resize-none"
            />
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-3"
              >Státusz váltás</label
            >
            <div class="grid grid-cols-3 gap-2">
              <button
                @click="activeStatus = 'Open'"
                :class="activeStatus == 'Open' ? 'bg-red-500 text-white' : 'bg-white/5 text-white/40'"
                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all"
              >
                <i class="fa-solid fa-envelope-open mb-1 block"></i> Nyitott
              </button>
              <button
                @click="activeStatus = 'Investigating'"
                :class="activeStatus == 'Investigating' ? 'bg-amber-500 text-black' : 'bg-white/5 text-white/40'"
                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all"
              >
                <i class="fa-solid fa-magnifying-glass mb-1 block"></i> Vizsgálat
              </button>
              <button
                @click="activeStatus = 'Closed'"
                :class="activeStatus == 'Closed' ? 'bg-accentGreen text-black' : 'bg-white/5 text-white/40'"
                class="py-3 rounded-xl font-bold uppercase text-[10px] transition-all"
              >
                <i class="fa-solid fa-check-double mb-1 block"></i> Lezárva
              </button>
            </div>
          </div>

          <div class="border-t border-white/5 pt-5">
            <button
              @click="showMatchDetails = !showMatchDetails"
              class="flex items-center gap-2 text-white/40 hover:text-white/70 text-[11px] font-bold uppercase tracking-widest transition-all"
            >
              <i :class="showMatchDetails ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
              Részletek
            </button>

            <div v-if="showMatchDetails" class="mt-4 flex flex-col gap-3">
              <div
                v-for="detail in selectedReport.match_details"
                :key="detail.id"
                class="bg-white/3 border border-white/5 rounded-2xl p-4 flex flex-col gap-3"
                :class="detail.round_number === selectedReport.round_number ? 'border-amber-500/20 bg-amber-500/5' : ''"
              >
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span
                    class="text-[10px] uppercase font-bold tracking-widest"
                    :class="detail.round_number === selectedReport.round_number ? 'text-amber-400' : 'text-white/30'"
                  >
                    {{ detail.round_number }}. forduló
                    <span v-if="detail.round_number === selectedReport.round_number" class="ml-1 text-amber-400/60"
                      >(bejelentett)</span
                    >
                  </span>
                  <span
                    class="text-[10px] font-bold font-mono px-2 py-0.5 rounded"
                    :class="
                      detail.user_guess_time_ms < 1000
                        ? 'text-red-400 bg-red-500/10'
                        : 'text-accentGreen bg-accentGreen/10'
                    "
                  >
                    {{ (detail.user_guess_time_ms / 1000).toFixed(2) }}s
                  </span>
                </div>

                <p class="text-white/70 text-sm leading-relaxed">
                  {{ detail.question?.question ?? "Ismeretlen kérdés" }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                  <div
                    class="flex items-center gap-2 px-3 py-2 rounded-xl border"
                    :class="
                      detail.user_answer_id === detail.correct_answer_id
                        ? 'bg-accentGreen/10 border-accentGreen/20 text-accentGreen'
                        : 'bg-red-500/10 border-red-500/20 text-red-400'
                    "
                  >
                    <i class="fa-solid fa-user text-xs shrink-0"></i>
                    <span>
                      <span class="text-white/30 text-[10px] uppercase font-bold block tracking-widest mb-0.5"
                        >Felhasználó válasza</span
                      >
                      <span v-if="detail.user_answer">{{ detail.user_answer.answer.replace("*", "") }}</span>
                      <span v-else class="italic opacity-60">(nem válaszolt)</span>
                    </span>
                  </div>

                  <div
                    class="flex items-center gap-2 px-3 py-2 rounded-xl border bg-accentGreen/10 border-accentGreen/20 text-accentGreen"
                  >
                    <i class="fa-solid fa-circle-check text-xs shrink-0"></i>
                    <span>
                      <span class="text-white/30 text-[10px] uppercase font-bold block tracking-widest mb-0.5"
                        >Helyes válasz</span
                      >
                      {{ detail.correct_answer?.answer.replace("*", "") ?? "–" }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="border-t border-white/5 pt-5">
            <button
              @click="showBanForm = !showBanForm"
              class="flex items-center gap-2 text-red-400/70 hover:text-red-400 text-[11px] font-bold uppercase tracking-widest transition-all"
            >
              <i :class="showBanForm ? 'fa-solid fa-chevron-up' : 'fa-solid fa-ban'"></i>
              {{ showBanForm ? "Kitiltás visszavonása" : "Játékos kitiltása" }}
            </button>

            <div
              v-if="showBanForm"
              class="mt-4 bg-red-500/5 border border-red-500/15 rounded-2xl p-5 flex flex-col gap-4"
            >
              <div>
                <label class="text-red-300/50 text-[10px] uppercase font-bold tracking-widest block mb-2"
                  >Kitiltás oka</label
                >
                <input
                  v-model="banForm.reason"
                  type="text"
                  placeholder="pl. Csalás, toxic viselkedés..."
                  class="w-full bg-white/5 border border-red-500/20 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/50 transition-all"
                />
              </div>
              <div>
                <label class="text-red-300/50 text-[10px] uppercase font-bold tracking-widest block mb-2"
                  >Lejárat dátuma</label
                >
                <input
                  v-model="banForm.release_date"
                  type="datetime-local"
                  class="w-full bg-white/5 border border-red-500/20 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/50 transition-all"
                />
              </div>
              <button
                @click="banUser"
                :disabled="isBanning"
                class="w-full bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-extrabold py-3 rounded-xl text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2"
              >
                <i class="fa-solid fa-ban"></i>
                {{ isBanning ? "Kitiltás folyamatban..." : `${selectedReport.user_reported?.name} kitiltása` }}
              </button>
            </div>
          </div>
        </div>

        <div class="flex justify-between items-center mt-10 pt-6 border-t border-white/5 sm:flex-row flex-col">
          <button
            @click="
              emit('deleteReport', selectedReport.id);
              isModalOpen = false;
            "
            class="group flex items-center gap-2 px-4 py-2 text-red-500/70 hover:text-red-500 hover:bg-red-500/5 rounded-xl cursor-pointer transition-all duration-300 ease-out active:scale-95 text-[10px] font-bold uppercase"
          >
            <i class="fa-solid fa-trash transition-transform group-hover:rotate-12 group-hover:scale-110"></i>
            Törlés
          </button>
          <div class="flex gap-4 sm:mt-0 mt-2">
            <button
              @click="isModalOpen = false"
              class="px-6 py-2 text-white/40 font-bold uppercase text-xs cursor-pointer hover:text-white hover:-translate-y-0.5 transition-all duration-200 ease-in-out flex items-center gap-2"
            >
              Bezárás
            </button>
            <button
              @click="saveUpdate()"
              :disabled="isSubmitting"
              class="bg-accentGreen text-black font-extrabold px-8 py-2 rounded-full text-xs hover:scale-105 transition-all shadow-lg shadow-accentGreen/20"
            >
              {{ isSubmitting ? "Mentés..." : "Rögzítés" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

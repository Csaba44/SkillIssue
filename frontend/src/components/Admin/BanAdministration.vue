<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../../config/api";
import { toast } from "vue-sonner";
import Select from "../Generic/Select.vue";

const bans = ref([]);
const users = ref([]);
const searchQuery = ref("");
const isLoading = ref(false);
const isModalOpen = ref(false);
const isAddModalOpen = ref(false);
const isSubmitting = ref(false);
const selectedBan = ref(null);

const editForm = ref({
  reason: "",
  release_date: "",
});

const addForm = ref({
  user_id: "",
  reason: "",
  release_date: "",
});

const getBans = async () => {
  isLoading.value = true;
  try {
    const res = await api.get("/api/ban");
    bans.value = res.data;
  } catch (error) {
    toast.error("Hiba a kitiltások lekérésekor.");
  } finally {
    isLoading.value = false;
  }
};

const getUsers = async () => {
  try {
    const res = await api.get("/api/users/all");
    users.value = res.data;
  } catch (error) {
    toast.error("Hiba a felhasználók lekérésekor.");
  }
};

const userOptions = computed(() =>
  users.value.map((u) => ({
    label: `#${u.id} — ${u.name} (${u.email})`,
    value: u.id,
  })),
);

const toUTCString = (localDateTime) => {
  if (!localDateTime) return null;
  const date = new Date(localDateTime);
  return date.toISOString().slice(0, 19).replace("T", " ");
};

const openDetails = (ban) => {
  selectedBan.value = ban;

  let formattedDate = "";
  if (ban.release_date) {
    const date = new Date(ban.release_date + "Z");
    const tzOffset = date.getTimezoneOffset() * 60000;
    const localISOTime = new Date(date - tzOffset).toISOString().slice(0, 16);
    formattedDate = localISOTime;
  }

  editForm.value = {
    reason: ban.reason ?? "",
    release_date: formattedDate,
  };
  isModalOpen.value = true;
};

const openAddModal = () => {
  addForm.value = { user_id: "", reason: "", release_date: "" };
  isAddModalOpen.value = true;
};

const saveUpdate = async () => {
  if (!editForm.value.reason || !editForm.value.release_date) {
    return toast.error("Kérjük töltsd ki az összes mezőt.");
  }

  isSubmitting.value = true;
  const utcDate = toUTCString(editForm.value.release_date);

  try {
    await api.put(`/api/ban/${selectedBan.value.id}`, {
      user_id: selectedBan.value.user_id,
      reason: editForm.value.reason,
      release_date: utcDate,
    });
    toast.success("Ban sikeresen frissítve!");
    isModalOpen.value = false;
    getBans();
  } catch (error) {
    toast.error("Hiba történt a mentéskor.");
  } finally {
    isSubmitting.value = false;
  }
};

const createBan = async () => {
  if (!addForm.value.user_id || !addForm.value.reason || !addForm.value.release_date) {
    return toast.error("Kérjük töltsd ki az összes mezőt.");
  }

  isSubmitting.value = true;
  const utcDate = toUTCString(addForm.value.release_date);

  try {
    await api.post("/api/ban", {
      user_id: addForm.value.user_id,
      reason: addForm.value.reason,
      release_date: utcDate,
    });
    toast.success("Kitiltás sikeresen létrehozva!");
    isAddModalOpen.value = false;
    getBans();
  } catch (error) {
    toast.error("Hiba történt a kitiltás létrehozásakor.");
  } finally {
    isSubmitting.value = false;
  }
};

const deleteBan = async (id) => {
  try {
    await api.delete(`/api/ban/${id}`);
    toast.success("Kitiltás törölve!");
    isModalOpen.value = false;
    getBans();
  } catch (error) {
    toast.error("Hiba a törlés során.");
  }
};

const isExpired = (releaseDate) => new Date(releaseDate) < new Date();

const formatDate = (dateStr) => {
  if (!dateStr) return "Ismeretlen";
  return new Date(dateStr + "Z").toLocaleString("hu-HU", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const filteredBans = computed(() => {
  const search = searchQuery.value.toLowerCase();
  return bans.value.filter((b) => {
    return !search || b.user?.name.toLowerCase().includes(search) || (b.reason ?? "").toLowerCase().includes(search);
  });
});

onMounted(() => {
  getBans();
  getUsers();
});
</script>

<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
      <div class="relative w-full lg:w-96">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/20"></i>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Felhasználó vagy indok keresése..."
          class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-white outline-none focus:border-red-500/50 transition-all"
        />
      </div>
      <div class="flex items-center gap-4">
        <div class="text-white/40 text-sm font-medium">
          Összesen: <span class="text-white">{{ bans.length }}</span> ban
        </div>
        <button
          @click="openAddModal"
          class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 font-bold uppercase text-[10px] tracking-widest px-4 py-2.5 rounded-xl transition-all"
        >
          <i class="fa-solid fa-plus"></i>
          Új kitiltás
        </button>
      </div>
    </div>

    <div class="grid gap-3">
      <div v-if="isLoading" class="py-20 text-center text-white/20 font-medium">
        <i class="fa-solid fa-spinner animate-spin mr-2"></i> Betöltés...
      </div>

      <template v-else-if="filteredBans.length > 0">
        <div
          v-for="ban in filteredBans"
          :key="ban.id"
          @click="openDetails(ban)"
          class="flex items-center justify-between gap-4 bg-white/5 hover:bg-white/8 border border-white/10 rounded-2xl px-5 py-4 cursor-pointer transition-all group"
          :class="{ 'opacity-40 grayscale': isExpired(ban.release_date) }"
        >
          <div class="flex items-center gap-4">
            <div
              class="w-9 h-9 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center shrink-0"
            >
              <i class="fa-solid fa-ban text-red-400 text-sm"></i>
            </div>
            <div>
              <p class="text-white font-semibold text-sm">{{ ban.user?.name ?? "Ismeretlen felhasználó" }}</p>
              <p class="text-white/40 text-xs mt-0.5 line-clamp-1">{{ ban.reason ?? "Nincs indok megadva" }}</p>
            </div>
          </div>

          <div class="flex items-center gap-4 shrink-0">
            <div class="text-right hidden sm:block">
              <p
                class="text-[10px] uppercase tracking-widest font-bold"
                :class="isExpired(ban.release_date) ? 'text-white/20' : 'text-red-400/70'"
              >
                {{ isExpired(ban.release_date) ? "Lejárt" : "Aktív" }}
              </p>
              <p class="text-white/40 text-xs mt-0.5">{{ formatDate(ban.release_date) }}</p>
            </div>
            <i class="fa-solid fa-chevron-right text-white/20 group-hover:text-white/40 transition-all text-xs"></i>
          </div>
        </div>
      </template>

      <div v-else class="py-20 text-center border border-dashed border-white/5 rounded-3xl">
        <p class="text-white/20 font-medium">Nincs kitiltás rekord.</p>
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
              <i class="fa-solid fa-ban text-red-500"></i> Ban részletei
            </h2>
            <div class="mt-2">
              <span
                class="text-[10px] uppercase font-black px-2 py-0.5 rounded tracking-widest"
                :class="isExpired(selectedBan.release_date) ? 'bg-white/5 text-white/30' : 'bg-red-500/10 text-red-400'"
              >
                {{ isExpired(selectedBan.release_date) ? "Lejárt" : "Aktív" }}
              </span>
            </div>
          </div>
        </div>

        <div class="space-y-6 text-left">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1"
                >Kitiltott felhasználó</label
              >
              <p class="text-white font-medium">{{ selectedBan.user?.name ?? "Ismeretlen" }}</p>
              <p class="text-white/30 text-xs mt-0.5">{{ selectedBan.user?.email }}</p>
            </div>
            <div>
              <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-1"
                >Ban létrehozva</label
              >
              <p class="text-white/60 text-sm">{{ formatDate(selectedBan.created_at) }}</p>
            </div>
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Ban oka</label>
            <input
              v-model="editForm.reason"
              type="text"
              placeholder="Ban oka..."
              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/40 transition-all"
            />
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2"
              >Lejárat dátuma</label
            >
            <input
              v-model="editForm.release_date"
              type="datetime-local"
              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/40 transition-all"
            />
          </div>
        </div>

        <div class="flex justify-between items-center mt-10 pt-6 border-t border-white/5 sm:flex-row flex-col">
          <button
            @click="deleteBan(selectedBan.id)"
            class="group flex items-center gap-2 px-4 py-2 text-red-500/70 hover:text-red-500 hover:bg-red-500/5 rounded-xl cursor-pointer transition-all duration-300 ease-out active:scale-95 text-[10px] font-bold uppercase"
          >
            <i class="fa-solid fa-trash transition-transform group-hover:rotate-12 group-hover:scale-110"></i>
            Ban törlése
          </button>
          <div class="flex gap-4 sm:mt-0 mt-2">
            <button
              @click="isModalOpen = false"
              class="px-6 py-2 text-white/40 font-bold uppercase text-xs cursor-pointer hover:text-white hover:-translate-y-0.5 transition-all duration-200 ease-in-out"
            >
              Bezárás
            </button>
            <button
              @click="saveUpdate()"
              :disabled="isSubmitting"
              class="bg-accentGreen text-black font-extrabold px-8 py-2 rounded-full text-xs hover:scale-105 transition-all shadow-lg shadow-accentGreen/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100"
            >
              {{ isSubmitting ? "Mentés..." : "Rögzítés" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="isAddModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
    >
      <div
        class="bg-bgDark border border-white/10 w-full max-w-xl rounded-3xl sm:p-8 p-3 shadow-2xl overflow-y-auto max-h-[95vh]"
      >
        <div class="flex justify-between items-start mb-8">
          <h2 class="text-xl font-bold text-white flex items-center gap-3">
            <i class="fa-solid fa-user-slash text-red-500"></i> Új kitiltás létrehozása
          </h2>
          <button @click="isAddModalOpen = false" class="text-white/20 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
          </button>
        </div>

        <div class="space-y-6 text-left">
          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Felhasználó</label>
            <Select
              :options="userOptions"
              v-model="addForm.user_id"
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white outline-none focus:border-red-500/40 transition-all"
            />
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2">Kitiltás oka</label>
            <input
              v-model="addForm.reason"
              type="text"
              placeholder="pl. Csalás, toxic viselkedés..."
              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/40 transition-all"
            />
          </div>

          <div>
            <label class="text-white/30 text-[10px] uppercase font-bold tracking-widest block mb-2"
              >Lejárat dátuma</label
            >
            <input
              v-model="addForm.release_date"
              type="datetime-local"
              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-red-500/40 transition-all"
            />
          </div>
        </div>

        <div class="flex justify-end gap-4 mt-10 pt-6 border-t border-white/5">
          <button
            @click="isAddModalOpen = false"
            class="px-6 py-2 text-white/40 font-bold uppercase text-xs cursor-pointer hover:text-white hover:-translate-y-0.5 transition-all duration-200 ease-in-out"
          >
            Mégse
          </button>
          <button
            @click="createBan()"
            :disabled="isSubmitting"
            class="bg-red-500 hover:bg-red-600 text-white font-extrabold px-8 py-2 rounded-full text-xs hover:scale-105 transition-all shadow-lg shadow-red-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100 flex items-center gap-2"
          >
            <i class="fa-solid fa-ban"></i>
            {{ isSubmitting ? "Létrehozás..." : "Kitiltás létrehozása" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

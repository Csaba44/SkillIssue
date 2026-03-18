<script setup>
import { ref, onMounted } from "vue";
import api from "../config/api";
import { socket } from "../config/websocket";

const apiStatus = ref("checking");
const wsStatus = ref("checking");

const apiError = ref(null);
const wsError = ref(null);

onMounted(async () => {
  checkAPI();
  checkWS();
});

async function checkAPI() {
  try {
    const res = await api.get("/api/health");

    if (res.status === 200) {
      apiStatus.value = "healthy";
    } else {
      apiStatus.value = "unhealthy";
    }
  } catch (err) {
    apiStatus.value = "unhealthy";
    apiError.value = err.message;
  }
}

function checkWS() {
  try {
    socket.on("connect", () => {
      socket.emit("test", { message: "ping" }, (data) => {
        if (data.message === "pong") {
          wsStatus.value = "healthy";
          socket.disconnect();
        }
      });
    });

    socket.on("connect_error", (err) => {
      wsStatus.value = "unhealthy";
      console.log(err);
      wsError.value = err.message;
    });

    socket.on("disconnect", (reason) => {
      if (wsStatus.value !== "healthy") {
        wsStatus.value = "unhealthy";
        wsError.value = reason;
      }
    });

    setTimeout(() => {
      if (wsStatus.value === "checking") {
        wsStatus.value = "unhealthy";
        wsError.value = "timeout";
        socket.disconnect();
      }
    }, 4000);
  } catch (err) {
    wsStatus.value = "unhealthy";
    wsError.value = err.message;
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-10">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-xl space-y-6">
      <h1 class="text-2xl font-bold text-gray-800">Health Check</h1>

      <div
        class="p-4 rounded-xl border flex justify-between items-center"
        :class="{
          'border-green-400 bg-green-50': apiStatus === 'healthy',
          'border-red-400 bg-red-50': apiStatus === 'unhealthy',
          'border-yellow-400 bg-yellow-50': apiStatus === 'checking',
        }"
      >
        <div>
          <div class="font-semibold">API</div>
          <div class="text-sm text-gray-600">/api/health</div>
          <div v-if="apiError" class="text-xs text-red-600 mt-1">
            {{ apiError }}
          </div>
        </div>

        <div class="font-bold">
          <span v-if="apiStatus === 'checking'">Checking...</span>
          <span v-if="apiStatus === 'healthy'">Healthy ✓</span>
          <span v-if="apiStatus === 'unhealthy'">Unhealthy ✗</span>
        </div>
      </div>

      <div
        class="p-4 rounded-xl border flex justify-between items-center"
        :class="{
          'border-green-400 bg-green-50': wsStatus === 'healthy',
          'border-red-400 bg-red-50': wsStatus === 'unhealthy',
          'border-yellow-400 bg-yellow-50': wsStatus === 'checking',
        }"
      >
        <div>
          <div class="font-semibold">WebSocket</div>
          <div class="text-sm text-gray-600">Socket.IO test event</div>
          <div v-if="wsError" class="text-xs text-red-600 mt-1">
            {{ wsError }}
          </div>
        </div>

        <div class="font-bold">
          <span v-if="wsStatus === 'checking'">Checking...</span>
          <span v-if="wsStatus === 'healthy'">Healthy ✓</span>
          <span v-if="wsStatus === 'unhealthy'">Unhealthy ✗</span>
        </div>
      </div>
    </div>
  </div>
</template>

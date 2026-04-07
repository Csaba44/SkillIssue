<script setup>
import { defineProps } from "vue";

const typeColors = {
  yellow: { colorVar: "var(--color-accentYellow)", iconColor: "#988433" },
  green: { colorVar: "var(--color-accentGreen)", iconColor: "#02805D" },
  purple: { colorVar: "var(--color-accentPurple)", iconColor: "#6146AC" },
  orange: { colorVar: "var(--color-accentOrange)", iconColor: "#993418" },
};

const props = defineProps({
  icon: { type: String, default: "fa-solid fa-flag" },
  type: { type: String, default: "purple" },
  title: { type: String, default: "Cím" },
  desc: { type: String, default: "Leírás" },
});
</script>

<template>
  <div
    class="bg-white relative flex flex-col mx-5 md:h-110 md:w-80 2xl:mx-auto my-5 h-100 w-75 items-center justify-between xl:h-100 xl:w-70 2xl:h-120 2xl:w-90 rounded-4xl overflow-hidden shadow-md transition-all duration-800 ease-out hover:-translate-y-1 hover:scale-[1.01] hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)]"
  >
    <div class="w-full h-full relative flex items-center justify-center">
      <svg
        v-if="icon.includes('bullseye')"
        class="absolute"
        width="50%"
        preserveAspectRatio
        viewBox="0 0 198 172"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M170.579 3.9454C188.586 12.1529 199.489 35.7954 196.549 56.2529C193.609 76.8329 176.826 94.2279 165.801 116.523C154.654 138.818 149.386 165.89 135.911 170.668C122.559 175.568 101.121 158.173 76.9889 148.985C52.8564 139.798 26.1514 138.818 12.4314 125.343C-1.4111 111.868 -2.1461 85.8979 2.7539 61.2754C7.5314 36.6529 17.9439 13.3779 35.4614 5.0479C52.9789 -3.2821 77.7239 3.3329 102.591 3.0879C127.459 2.8429 152.571 -4.2621 170.579 3.9454Z"
          fill="#FBD22F"
        />
      </svg>

      <svg
        v-if="icon.includes('user')"
        class="absolute mt-5"
        width="50%"
        preserveAspectRatio
        viewBox="0 0 157 184"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M142.955 12.024C155.104 22.971 156.706 45.7995 156.706 65.8245C156.572 85.8495 154.97 103.071 150.431 125.899C145.759 148.728 138.283 177.163 122.663 182.637C107.044 188.11 83.1474 170.622 61.5204 158.073C39.8934 145.524 20.4024 137.781 10.1229 123.229C-0.156638 108.678 -1.22464 87.318 0.91136 64.7565C2.91386 42.195 8.12036 18.699 22.6719 8.15248C37.3569 -2.39402 61.3869 0.00898052 84.8829 0.809981C108.245 1.74448 130.807 0.943477 142.955 12.024Z"
          fill="#FE4C01"
        />
      </svg>

      <svg
        v-if="icon.includes('dumbbell')"
        class="absolute"
        width="50%"
        preserveAspectRatio
        viewBox="0 0 169 186"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M136.012 23.699C150.188 35.984 156.398 57.584 162.068 80.534C167.738 103.484 173.003 127.919 164.632 146.279C156.128 164.639 134.122 177.059 111.713 182.594C89.3025 188.129 66.6225 186.779 47.9925 178.274C29.4975 169.769 14.9175 154.109 8.7075 136.694C2.3625 119.414 4.1175 100.379 2.4975 75.809C0.877498 51.239 -4.2525 20.999 7.6275 8.17396C19.5075 -4.51604 48.3975 0.343955 73.9125 4.39395C99.5625 8.30896 121.702 11.414 136.012 23.699Z"
          fill="#02CB74"
        />
      </svg>

      <svg
        v-if="icon.includes('hourglass')"
        class="absolute"
        width="50%"
        preserveAspectRatio
        viewBox="0 0 199 197"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M172.689 6.03863C190.02 16.2146 198.606 41.4956 198.129 64.5506C197.652 87.6056 188.271 108.435 178.413 132.126C168.396 155.976 157.902 182.529 138.981 192.069C120.219 201.609 92.8712 194.136 67.1132 183.96C41.5142 173.784 17.5052 160.905 6.85217 141.03C-3.80083 121.314 -0.938838 94.4426 7.96516 72.8186C16.8692 51.1946 31.8152 34.9766 48.5102 24.6416C65.2052 14.3066 83.8082 9.85463 106.386 5.24363C128.805 0.632633 155.199 -4.13737 172.689 6.03863Z"
          fill="#A06BF9"
        />
      </svg>

      <i
        class="text-6xl md:top-23 text-bgDark/40 md:text-7xl xl:top-20 2xl:top-25 xl:text-7xl z-10"
        :class="props.icon"
      ></i>
    </div>

    <p class="z-10 px-1 font-bold text-black text-4xl xl:text-3xl 2xl:text-4xl text-center">
      {{ props.title }}
    </p>

    <div class="px-3 w-full">
      <p
        class="z-10 text-lg text-gray-500 text-center mb-8 px-2 overflow-hidden overflow-ellipsis wrap-break-word max-h-28"
      >
        {{ props.desc }}
      </p>
    </div>
  </div>
</template>

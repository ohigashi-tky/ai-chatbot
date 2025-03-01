<template>
  <header class="bg-gradient-to-r from-gray-500 to-gray-600 p-3 flex items-center shadow-md">
    <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
      <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="4" y="7" width="16" height="12" rx="4" stroke="#4F46E5" stroke-width="2"/>
        <circle cx="8.5" cy="13" r="1.5" fill="#4F46E5"/>
        <circle cx="15.5" cy="13" r="1.5" fill="#4F46E5"/>
        <path d="M12 2V6M6 3V6M18 3V6" stroke="#4F46E5" stroke-width="2"/>
      </svg>
    </div>
    <h1 class="text-white font-bold text-xl">バーチャルCTO</h1>
    <div class="flex-1"></div>
    <button @click="toggleDarkMode" class="text-white p-2 rounded-full hover:bg-white/10">
      <svg v-if="isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
      </svg>
    </button>
  </header>
</template>

<script setup>
import { ref } from 'vue';

const isDark = ref(localStorage.getItem('darkMode') === 'true');

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  localStorage.setItem('darkMode', isDark.value);
  
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

// 初期化時にダークモード設定を適用
if (isDark.value) {
  document.documentElement.classList.add('dark');
}
</script>

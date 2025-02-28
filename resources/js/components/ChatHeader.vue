<template>
  <header class="bg-gradient-to-r from-gray-400 to-gray-600 p-3 flex items-center shadow-md">
    <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
      <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 7H7v6h6V7z" />
        <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd" />
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

<template>
  <header 
    :class="[
      'p-3 flex items-center shadow-md transition-colors duration-300',
      isDark ? 'bg-gray-800' : 'bg-gray-200'
    ]"
  >
    <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
      <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="4" y="7" width="16" height="12" rx="4" stroke="#4F46E5" stroke-width="2"/>
        <circle cx="8.5" cy="13" r="1.5" fill="#4F46E5"/>
        <circle cx="15.5" cy="13" r="1.5" fill="#4F46E5"/>
        <path d="M12 2V6M6 3V6M18 3V6" stroke="#4F46E5" stroke-width="2"/>
      </svg>
    </div>
    <h1 class="font-bold text-xl" :class="isDark ? 'text-gray-100' : 'text-gray-900'">バーチャルCTO</h1>
    <div class="flex-1"></div>
    <button @click="toggleDarkMode" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
      <svg v-if="isDark" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
      </svg>
      <svg v-else class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
      </svg>
    </button>
  </header>
</template>

<script setup>
import { inject } from "vue";

const isDark = inject("isDark");
const toggleDarkMode = inject("toggleDarkMode");
</script>

<template>
    <div :class="[
          'p-4 duration-300',
          isDark ? 'bg-gray-800' : 'bg-gray-200'
        ]">
    <div class="max-w-3xl mx-auto flex items-center">
      <input
        v-model="message"
        type="text"
        placeholder="文字を入力..."
        :class="[
          'flex-1 rounded-full py-2 px-4 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 text-black',
          isDark ? 'bg-gray-500 placeholder-gray-100' : 'bg-white placeholder-gray-600'
        ]"
        :disabled="isLoading"
        @keyup.enter="sendMessage"
      />
      <button
        @click="sendMessage"
        :disabled="isLoading || !message.trim()"
        class="ml-2 bg-gray-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <svg v-if="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        <svg v-else class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, inject} from 'vue';

const isDark = inject('isDark', ref(false));

const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false
  }
});

const message = ref('');
const emit = defineEmits(['sendMessage']);

const sendMessage = () => {
  if (message.value.trim() && !props.isLoading) {
    emit('sendMessage', message.value);
    message.value = '';
  }
};
</script>

<template>
  <div ref="chatContainer" class="chat-area">
    <div class="text-sm m-2 p-2 bg-gray-200 rounded-md"
      v-for="(message, index) in messages"
      :key="index"
      :class="['message', message.sender]"
    >
      <div class="flex items-center">
        <VProgressCircular
          v-if="message.isLoading"
          indeterminate
          color="blue-lighten-3"
          :size="24"
          :width="4"
          class="loading-spinner"
        ></VProgressCircular>
        <span v-else v-html="message.text"></span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { VProgressCircular } from 'vuetify/components'

const props = defineProps({
  messages: {
    type: Array,
    required: true,
  },
})

const chatContainer = ref(null)

watch(() => props.messages.length, async () => {
  await nextTick()
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight
  }
})
</script>

<style scoped>
.chat-area {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  background-color: #f9f9f9;
  height: calc(100vh - 150px);
}

.loading-spinner {
  margin: 0 auto;
}
</style>
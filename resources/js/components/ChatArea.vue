<template>
  <div ref="chatContainer" class="chat-area">
    <div class="m-2 p-2 rounded-md"
      v-for="(message, index) in messages"
      :key="index"
      :class="['message', message.sender]"
    >
      <div class="flex items-center">
        <VProgressCircular
          v-if="message.isLoading"
          indeterminate
          color="blue-lighten-3"
          :size="40"
          :width="8"
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

.message.user {
  background-color: #d1e7dd;
  width: auto;
  max-width: 70%;
  align-self: flex-end;
  margin-left: auto;
}

.message.bot {
  background-color: #f8d7da;
  align-self: flex-start;
}

.loading-spinner {
  margin: 0 auto;
}
</style>
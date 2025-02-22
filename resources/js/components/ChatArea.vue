<template>
  <div class="chat-area">
    <div
      v-for="(message, index) in messages"
      :key="index"
      :class="['message', message.sender]"
    >
      <span v-if="message.isLoading">回答を生成中です...</span>
      <span v-else v-html="message.text"></span>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
  messages: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.chat-area {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  background-color: #f9f9f9;
}
.message {
  margin: 5px 0;
  padding: 10px;
  border-radius: 5px;
}
.message.user {
  background-color: #d1e7dd;
  align-self: flex-end;
}
.message.bot {
  background-color: #f8d7da;
  align-self: flex-start;
}
</style>

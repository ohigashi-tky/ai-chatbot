<template>
  <div class="chat-layout">
    <ChatHeader />
    <ChatArea :messages="messages" />
    <ChatInput @sendMessage="handleSendMessage" />
  </div>
</template>

<script setup>
import { ref, nextTick } from "vue";
import axios from "axios";
import ChatHeader from "./ChatHeader.vue";
import ChatArea from "./ChatArea.vue";
import ChatInput from "./ChatInput.vue";

// メッセージのリスト
const messages = ref([
  { text: "こんにちは。何かお手伝いできることはありますか？", sender: "bot" },
]);

const handleSendMessage = async (message) => {
  messages.value.push({ text: message, sender: "user" });

  // 回答待ちのローディングを追加
  const botMessage = { text: "", sender: "bot", isLoading: true };
  messages.value.push(botMessage);

  await nextTick();

  try {
    const response = await axios.post("/api/chat", { message });

    await nextTick();
    botMessage.text = response.data.reply;
    botMessage.isLoading = false;

    messages.value = [...messages.value];
  } catch (error) {
    await nextTick();
    botMessage.text = "エラーが発生しました。";
    botMessage.isLoading = false;

    messages.value = [...messages.value];
  }
};

</script>

<style scoped>
.chat-layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
</style>

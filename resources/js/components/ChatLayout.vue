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
  { text: "こんにちは。システム開発や技術についての相談はありますか？", sender: "bot" },
]);

const handleSendMessage = async (message) => {
  messages.value.push({ text: message, sender: "user" });

  const botMessage = { text: "", sender: "bot", isLoading: true };
  messages.value.push(botMessage);

  await nextTick();

  try {
    // AI未使用のテスト用
    let isTest = false;

    if (isTest) {
      await sleep(3000);
      const response = '回答'
      await nextTick();
      botMessage.text = response;
    } else {
      const response = await axios.post("/api/chat", { message });
      await nextTick();
      botMessage.text = response.data.reply;
    }

    botMessage.isLoading = false;

    messages.value = [...messages.value];
  } catch (error) {
    await nextTick();
    botMessage.text = "エラーが発生しました。";
    botMessage.isLoading = false;

    messages.value = [...messages.value];
  }
};

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

</script>

<style scoped>
.chat-layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
</style>

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

// 初期メッセージのリスト
const messages = ref([
  { text: "こんにちは。何かお手伝いできることはありますか？", sender: "bot" },
]);

const handleSendMessage = async (message) => {
  // ユーザーのメッセージ追加
  messages.value.push({ text: message, sender: "user" });

  messages.value.push({ text: "回答を生成中です...", sender: "bot", isLoading: true });

  // 回答のメッセージ欄を保持（回答で上書きするため）
  const botMessageIndex = messages.value.length - 1;

  try {
    const response = await axios.post("/api/chat", { message });

    // 変更を検知できるように
    await nextTick();

    messages.value[botMessageIndex] = {
      text: response.data.reply,
      sender: "bot",
      isLoading: false,
    };
  } catch (error) {
    // エラー発生時
    await nextTick();
    messages.value[botMessageIndex] = {
      text: "エラーが発生しました。",
      sender: "bot",
      isLoading: false,
    };
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

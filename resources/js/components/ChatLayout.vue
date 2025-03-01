<template>
  <div class="h-screen w-full flex flex-col bg-gray-100 dark:bg-gray-900">
    <div class="flex flex-col h-full w-full max-w-4xl mx-auto">
      <ChatHeader />
      <ChatArea :messages="messages" class="flex-1 overflow-y-auto" />
      <ChatInput @sendMessage="handleSendMessage" :isLoading="isLoading" />
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from "vue";
import axios from "axios";
import ChatHeader from "./ChatHeader.vue";
import ChatArea from "./ChatArea.vue";
import ChatInput from "./ChatInput.vue";

const isLoading = ref(false);

// メッセージのリスト
const messages = ref([
  { 
    text: "こんにちは。システム開発や技術についての相談はありますか？", 
    sender: "bot" 
  },
]);

const handleSendMessage = async (message) => {
  messages.value.push({ 
    text: message, 
    sender: "user" 
  });

  const botMessage = { 
    text: "",
    sender: "bot",
    isLoading: true
  };
  
  messages.value.push(botMessage);
  isLoading.value = true;

  try {
    console.log("APIリクエスト送信中...");
    const response = await axios.post("/api/chat", { message });
    console.log("APIレスポンス:", response.data);
    console.log("APIチャートデータ:", response.data.chartData);
    
    botMessage.isLoading = false;
    botMessage.text = response.data.reply;
    
    // グラフデータがあれば追加
    if (response.data.chartData) {
      botMessage.hasChart = true;
      botMessage.chartData = response.data.chartData;
      botMessage.chartType = response.data.chartType || 'bar';
      botMessage.chartOptions = response.data.chartOptions || {};
    }
    
    messages.value = [...messages.value];
    isLoading.value = false;
  } catch (error) {
    console.error("エラー発生:", error);
    botMessage.isLoading = false;
    botMessage.text = "エラーが発生しました。もう一度お試しください。";
    messages.value = [...messages.value];
    isLoading.value = false;
  }
};
</script>

<template>
    <div class="chat-layout">
      <ChatHeader />
      <ChatArea :messages="messages" />
      <ChatInput @sendMessage="handleSendMessage" />
    </div>
  </template>
  
  <script>
  import ChatHeader from './ChatHeader.vue';
  import ChatArea from './ChatArea.vue';
  import ChatInput from './ChatInput.vue';
  import axios from 'axios';
  
  export default {
    name: 'ChatLayout',
    components: {
      ChatHeader,
      ChatArea,
      ChatInput,
    },
    data() {
      return {
        messages: [], // チャットメッセージを管理
      };
    },
    mounted() {
        // 初期メッセージ
        this.messages.push({ text: 'こんにちは。何かお手伝いできることはありますか？', sender: 'bot' });
    },
    methods: {
      async handleSendMessage(message) {
        // ユーザーからのメッセージを追加
        this.messages.push({ text: message, sender: 'user' });
  
        try {
          // Laravel API にメッセージを送信
          const response = await axios.post('/api/chat', { message });
  
          // Amazon Lex の応答を追加
          this.messages.push({ text: response.data.reply, sender: 'bot' });
        } catch (error) {
          this.messages.push({ text: 'エラーが発生しました。', sender: 'bot' });
        }
      },
    },
  };
  </script>  
  
  <style>
  .chat-layout {
    display: flex;
    flex-direction: column;
    height: 100vh;
  }
  </style>
  
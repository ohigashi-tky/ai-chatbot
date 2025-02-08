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
    methods: {
      handleSendMessage(message) {
        // ユーザーからのメッセージを追加
        this.messages.push({ text: message, sender: 'user' });
  
        // ボットの応答を追加（ここでAPI呼び出し）
        if (message == 'こんにちは') {
            this.messages.push({ text: 'こんにちは。何が知りたいですか？', sender: 'bot' });
        } else {
            this.messages.push({ text: 'すみません。理解できませんでした。', sender: 'bot' });
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
  
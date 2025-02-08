<template>
    <div class="chat-input">
      <input
        v-model="message"
        type="text"
        placeholder="文字を入力..."
        @keyup.enter="sendMessage"
      />
      <button @click="sendMessage">Send</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ChatInput',
    data() {
      return {
        message: '',
      };
    },
    methods: {
      sendMessage() {
        if (this.message.trim()) {
          this.$emit('sendMessage', this.message);
          this.message = '';
        }
      },
    },
  };
  </script>
  
  <style>
  .chat-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ddd;
  }
  .chat-input input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  .chat-input button {
    margin-left: 10px;
    padding: 10px 20px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  </style>

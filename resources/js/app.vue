<template>
  <div class="tw-h-screen tw-w-full">
    <ChatLayout />
  </div>
</template>

<script>
import ChatLayout from './components/ChatLayout.vue';

export default {
  name: 'App',
  components: {
    ChatLayout,
  },
};
</script>

<style>
h1 {
  color: blue;
}
</style>

<template>
  <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 bg-gray-300 relative">
    <div class="max-w-3xl mx-auto">
      <div
        v-for="(message, index) in messages"
        :key="index"
        :class="[
          'my-3',
          message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'
        ]"
      >
        <div
          :class="[
            'max-w-[100%] rounded-2xl p-3 shadow-sm',
            message.sender === 'user' 
              ? 'text-sm bg-gray-500 text-gray-100 rounded-tr-none' 
              : 'text-sm bg-gray-600 text-gray-100 rounded-tl-none'
          ]"
        >
          <div class="flex items-center">
            <div v-if="message.isLoading" class="flex space-x-1 mx-auto my-2">
              <div class="w-2 h-2 bg-gray-900 rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-gray-900 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
              <div class="w-2 h-2 bg-gray-900 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
            <div v-else>
              <!-- 通常のテキストメッセージ -->
              <span v-if="!message.hasChart" v-html="message.text" class="leading-relaxed break-words"></span>
              
              <!-- グラフを含むメッセージ -->
              <div v-else>
                <div class="bg-white p-2 rounded-lg mb-3 w-full">
                  <chart-component 
                    v-if="message.chartData" 
                    :chart-data="message.chartData" 
                    :chart-type="message.chartType || 'bar'"
                    :chart-options="message.chartOptions || {}"
                    class="w-full h-80"
                  />
                </div>
                <span v-html="message.text" class="leading-relaxed break-words mt-2 block"></span>
              </div>
            </div>
          </div>
          <div 
            :class="[
              'text-xs mt-1 text-right',
              message.sender === 'user' ? 'text-indigo-200' : 'text-gray-500 dark:text-gray-400'
            ]"
          >
            {{ getCurrentTime() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import ChartComponent from './ChartComponent.vue'

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

const getCurrentTime = () => {
  const now = new Date()
  return `${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}`
}
</script>

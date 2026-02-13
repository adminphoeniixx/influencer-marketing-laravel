<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  collapsed: {
    type: Boolean,
    default: false,
  },
})

const logout = () => {
  router.post('/logout')
}
</script>

<template>
  <button
    @click="logout"
    class="flex items-center gap-3 w-full px-4 py-2 rounded
           text-red-600 hover:bg-red-50 transition"
    :class="{
      'justify-center px-2': collapsed,
    }"
  >
    <span class="text-lg">🚪</span>
    <span v-if="!collapsed">Logout</span>
  </button>
</template>
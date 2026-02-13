<script setup>
import { Link, usePage } from '@inertiajs/vue3'

defineProps({
  href: String,
  icon: String,
  collapsed: Boolean,
})
</script>

<template>
 <Link
  :href="href"
  class="flex items-center gap-3 px-4 py-2 rounded
         text-gray-600
         hover:bg-gray-800 hover:text-white
         transition-colors duration-150"
  :class="{
    'bg-gray-800 text-white font-semibold': usePage().url.startsWith(href),
    'justify-center': collapsed,
  }"
>
  <span class="text-lg transition-colors duration-150">
    {{ icon }}
  </span>

  <span v-if="!collapsed" class="truncate">
    <slot />
  </span>
</Link>
</template>
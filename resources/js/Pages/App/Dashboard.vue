<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'

const role = usePage().props.auth.user.roles[0]
</script>

<template>
  <AppLayout>
    <h1 class="text-2xl font-bold">
      {{ role === 'creator' ? 'Creator Dashboard' : 'Brand Dashboard' }}
    </h1>
  </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user
</script>

<template>
  <AppLayout>
    <div class="flex items-center justify-center min-h-[70vh]">
      <div class="max-w-xl w-full bg-white border rounded-lg p-8 text-center">
        <h1 class="text-2xl font-bold mb-2">
          Welcome, {{ user.name }} 👋
        </h1>

        <p class="text-gray-600 mb-6">
          Find creators, launch campaigns, and collaborate with influencers.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <a
            href="/app/creators"
            class="block p-4 border rounded hover:bg-gray-50 transition"
          >
            <div class="text-lg font-semibold">Browse Creators</div>
            <div class="text-sm text-gray-500">
              Discover creators across platforms
            </div>
          </a>

          <a
            href="/app/campaigns/create"
            class="block p-4 border rounded hover:bg-gray-50 transition"
          >
            <div class="text-lg font-semibold">Create Campaign</div>
            <div class="text-sm text-gray-500">
              Launch a new influencer campaign
            </div>
          </a>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
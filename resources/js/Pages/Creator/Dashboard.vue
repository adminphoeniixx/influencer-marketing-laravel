<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

const loading = ref(true)
const data = ref(null)

const page = usePage()
const userName = computed(() => page.props.auth.user.name)

onMounted(async () => {
  try {
    const res = await axios.get('/app/creator/dashboard')
    data.value = res.data.data
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AppLayout>
    <!-- Center wrapper -->
    <div class="w-full flex justify-center">
      <div class="w-full max-w-5xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold">
              Hi, {{ userName }} 👋
            </h1>
            <p class="text-sm text-gray-600">
              Here’s what’s happening with your creator profile
            </p>
          </div>

          <Link
            href="/app/creator/profile-view"
            class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800"
          >
            Edit Profile
          </Link>
        </div>

        <div v-if="loading" class="mt-6 text-center text-gray-600">
          Loading dashboard…
        </div>

        <div v-else class="space-y-4">
          <!-- No profile -->
          <div
            v-if="!data?.has_profile"
            class="p-6 bg-yellow-50 border rounded text-center"
          >
            <div class="font-semibold text-lg">
              No profile found
            </div>
            <div class="text-sm text-gray-600 mt-1">
              Create your creator profile to get approved and start receiving campaigns.
            </div>

            <Link
              href="/app/creator/profile-view"
              class="inline-block mt-4 px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800"
            >
              Create Profile
            </Link>
          </div>

          <!-- Profile exists -->
          <div v-else class="p-6 bg-white border rounded">
            <!-- Profile header -->
            <div class="flex items-center justify-between">
              <div>
                <div class="font-semibold text-lg">
                  {{ data.profile.display_name }}
                </div>
                <div class="text-sm text-gray-600">
                  {{ data.profile.location }}
                </div>
              </div>

              <span
                class="px-3 py-1 rounded text-sm capitalize"
                :class="{
                  'bg-yellow-100 text-yellow-800': data.status === 'pending',
                  'bg-green-100 text-green-800': data.status === 'approved',
                  'bg-red-100 text-red-800': data.status === 'rejected'
                }"
              >
                {{ data.status }}
              </span>
            </div>

            <!-- Bio -->
            <div class="mt-3 text-sm text-gray-700">
              {{ data.profile.bio }}
            </div>

            <!-- Stats -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="p-4 border rounded bg-gray-50 text-center">
                <div class="text-xs text-gray-500">Profile views</div>
                <div class="text-2xl font-bold">
                  {{ data.stats.profile_views }}
                </div>
              </div>

              <div class="p-4 border rounded bg-gray-50 text-center">
                <div class="text-xs text-gray-500">Shortlisted</div>
                <div class="text-2xl font-bold">
                  {{ data.stats.shortlisted }}
                </div>
              </div>

              <div class="p-4 border rounded bg-gray-50 text-center">
                <div class="text-xs text-gray-500">Campaigns</div>
                <div class="text-2xl font-bold">
                  {{ data.stats.campaigns }}
                </div>
              </div>
            </div>

            <!-- Rejected notice -->
            <div
              v-if="data.status === 'rejected'"
              class="mt-6 p-4 bg-red-50 border rounded"
            >
              <div class="font-semibold text-red-700">
                Profile rejected
              </div>
              <div class="text-sm text-red-600">
                Please update your details and resubmit for review.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
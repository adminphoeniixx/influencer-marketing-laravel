<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'

/* ───────────────── PLATFORM → SERVICES MAP ───────────────── */
const PLATFORM_SERVICES = {
  instagram: [
    { key: 'post', label: 'Post' },
    { key: 'story', label: 'Story' },
    { key: 'reel', label: 'Reel' },
  ],
  youtube: [
    { key: 'short', label: 'Short' },
    { key: 'video', label: 'Video' },
    { key: 'community', label: 'Community Post' },
  ],
  twitter: [
    { key: 'tweet', label: 'Tweet' },
    { key: 'thread', label: 'Thread' },
  ],
}

/* ───────────────── HELPERS ───────────────── */
const servicesForPlatform = (platform) =>
  PLATFORM_SERVICES[platform] || []

const initServicesForPlatform = (platform) => {
  const services = {}
  servicesForPlatform(platform).forEach(s => {
    services[s.key] = 0
  })
  return services
}

const emptyPlatform = () => ({
  platform: 'instagram',
  username: '',
  followers: 0,
  engagement: null,
  services: initServicesForPlatform('instagram'),
})

/* ───────────────── STATE ───────────────── */
const loading = ref(true)
const saving = ref(false)
const error = ref(null)

const form = ref({
  display_name: '',
  bio: '',
  location: '',
  categories: ['Tech'],
  languages: ['English'],
  platforms: [emptyPlatform()],
})

/* ───────────────── WATCH PLATFORM CHANGE (CRITICAL FIX) ───────────────── */
watch(
  () => form.value.platforms.map(p => p.platform),
  () => {
    form.value.platforms.forEach(p => {
      if (!p.services || typeof p.services !== 'object') {
        p.services = initServicesForPlatform(p.platform)
        return
      }

      const allowedKeys = servicesForPlatform(p.platform).map(s => s.key)

      // Remove old keys
      Object.keys(p.services).forEach(key => {
        if (!allowedKeys.includes(key)) {
          delete p.services[key]
        }
      })

      // Add missing keys
      allowedKeys.forEach(key => {
        if (!(key in p.services)) {
          p.services[key] = 0
        }
      })
    })
  },
  { deep: true }
)

/* ───────────────── ACTIONS ───────────────── */
const addPlatform = () => {
  form.value.platforms.push(emptyPlatform())
}

const removePlatform = (index) => {
  if (form.value.platforms.length > 1) {
    form.value.platforms.splice(index, 1)
  }
}

const submit = async () => {
  saving.value = true
  error.value = null

  try {
    await axios.post('/creator/profile', form.value)
    router.visit('/app/creator/dashboard-view')
  } catch (e) {
    error.value = e?.response?.data?.message || 'Validation error'
  } finally {
    saving.value = false
  }
}

/* ───────────────── LOAD EXISTING PROFILE ───────────────── */
onMounted(async () => {
  try {
    const res = await axios.get('/creator/profile')

    if (res.data?.data) {
      const p = res.data.data

      form.value.display_name = p.display_name ?? ''
      form.value.bio          = p.bio ?? ''
      form.value.location     = p.location ?? ''
      form.value.categories  = p.categories ?? []
      form.value.languages   = p.languages ?? []

      form.value.platforms = (p.platforms || []).map(pl => ({
        platform: pl.platform,
        username: pl.username ?? '',
        followers: pl.followers ?? 0,
        engagement: pl.engagement ?? null,
        services: pl.services
          ? { ...initServicesForPlatform(pl.platform), ...pl.services }
          : initServicesForPlatform(pl.platform),
      }))

      if (!form.value.platforms.length) {
        form.value.platforms = [emptyPlatform()]
      }
    }
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Creator Profile</h1>

      <div v-if="error" class="mb-4 p-3 bg-red-50 border rounded text-red-700">
        {{ error }}
      </div>

      <div class="bg-white border rounded p-6 space-y-6">
        <!-- BASIC INFO -->
        <div>
          <label class="text-sm font-medium">Display name</label>
          <input v-model="form.display_name" class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="text-sm font-medium">Bio</label>
          <textarea v-model="form.bio" rows="4" class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="text-sm font-medium">Location</label>
          <input v-model="form.location" class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <!-- PLATFORMS -->
        <div class="border-t pt-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg">Platforms</h2>
            <button @click="addPlatform" class="text-sm text-indigo-600 hover:underline">
              + Add platform
            </button>
          </div>

          <div
            v-for="(p, i) in form.platforms"
            :key="i"
            class="border rounded p-4 mb-4 space-y-4"
          >
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="text-sm font-medium">Platform</label>
                <select v-model="p.platform" class="mt-1 w-full border rounded px-3 py-2">
                  <option value="instagram">Instagram</option>
                  <option value="youtube">YouTube</option>
                  <option value="twitter">Twitter</option>
                </select>
              </div>

              <div>
                <label class="text-sm font-medium">Username</label>
                <input v-model="p.username" class="mt-1 w-full border rounded px-3 py-2" />
              </div>

              <div>
                <label class="text-sm font-medium">Followers</label>
                <input type="number" v-model.number="p.followers" class="mt-1 w-full border rounded px-3 py-2" />
              </div>
            </div>

            <!-- SERVICES -->
            <div>
              <label class="text-sm font-medium block mb-2">Service pricing</label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div
                  v-for="service in servicesForPlatform(p.platform)"
                  :key="service.key"
                >
                  <label class="text-xs text-gray-600">{{ service.label }}</label>
                  <input
                    type="number"
                    v-model.number="p.services[service.key]"
                    class="mt-1 w-full border rounded px-3 py-2"
                  />
                </div>
              </div>
            </div>

            <div class="text-right">
              <button
                v-if="form.platforms.length > 1"
                @click="removePlatform(i)"
                class="text-sm text-red-600 hover:underline"
              >
                Remove platform
              </button>
            </div>
          </div>
        </div>

        <!-- SUBMIT -->
        <div class="pt-4 flex justify-end">
          <PrimaryButton :disabled="saving" @click="submit">
            {{ saving ? 'Saving...' : 'Submit for Review' }}
          </PrimaryButton>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
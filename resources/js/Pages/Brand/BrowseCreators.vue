<script setup>
import { computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import MarketplaceLayout from '@/Layouts/MarketplaceLayout.vue'

const page = usePage()

const creators = computed(() => page.props.creators || [])
const meta = computed(() => page.props.meta || { page: 1, per_page: 12, total: 0 })

// ---- filters (query-string driven) ----
const qs = computed(() => page.props.ziggy?.query || {}) // optional
// safer: read from window location
const urlParams = new URLSearchParams(typeof window !== 'undefined' ? window.location.search : '')
const qInit = urlParams.get('q') || ''
const categoryInit = urlParams.get('category') || ''
const locationInit = urlParams.get('location') || ''
const platformInit = urlParams.get('platform') || ''

const q = ref(qInit)
const category = ref(categoryInit)
const location = ref(locationInit)
const platform = ref(platformInit)

const applyFilters = () => {
  router.get(
    '/app/creators',
    {
      q: q.value || undefined,
      category: category.value || undefined,
      location: location.value || undefined,
      platform: platform.value || undefined,
      page: 1,
    },
    { preserveScroll: true, preserveState: true }
  )
}

const resetFilters = () => {
  q.value = ''
  category.value = ''
  location.value = ''
  platform.value = ''
  router.get('/app/creators', {}, { preserveScroll: true })
}

// ---- helpers ----
const formatMoney = (n) => {
  const num = Number(n || 0)
  return num.toLocaleString('en-IN')
}

const formatFollowers = (n) => {
  const num = Number(n || 0)
  if (num >= 1_000_000) return (num / 1_000_000).toFixed(1) + 'M'
  if (num >= 1_000) return (num / 1_000).toFixed(1) + 'K'
  return String(num)
}

const getMaxFollowers = (platforms = []) => {
  let max = 0
  platforms.forEach(p => {
    const f = Number(p?.followers || 0)
    if (f > max) max = f
  })
  return max
}

const getAvgEng = (platforms = []) => {
  const vals = platforms.map(p => Number(p?.engagement)).filter(v => !Number.isNaN(v))
  if (!vals.length) return null
  return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1)
}

const platformIcon = (name) => {
  const n = (name || '').toLowerCase()
  if (n === 'instagram') return '📸'
  if (n === 'youtube') return '▶️'
  if (n === 'tiktok') return '🎵'
  if (n === 'twitter' || n === 'x') return '𝕏'
  return '✨'
}
</script>

<template>
  <MarketplaceLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
      <!-- header -->
      <div class="flex flex-col gap-2">
        <div class="inline-flex items-center gap-2 self-start px-4 py-2 rounded-full border border-[#c8ff00]/20 bg-[#c8ff00]/10 text-[#c8ff00] text-sm">
          <span class="w-2 h-2 rounded-full bg-[#c8ff00] animate-pulse" />
          Discover creators
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
          Find creators by <span class="text-transparent" style="-webkit-text-stroke: 1.2px #c8ff00;">signal</span>, not noise
        </h1>

        <p class="text-[#7070a0] max-w-2xl">
          Filter by platform, category, location. View verified profiles and pricing.
        </p>
      </div>

      <!-- search bar -->
      <div class="mt-8 bg-[#0e0e1a] border border-white/10 rounded-2xl p-4">
        <div class="flex flex-col lg:flex-row gap-3 lg:items-center">
          <div class="flex-1 flex items-center gap-3 rounded-xl border border-white/10 bg-black/20 px-4 py-3">
            <span class="text-[#7070a0]">🔍</span>
            <input
              v-model="q"
              @keyup.enter="applyFilters"
              class="w-full bg-transparent outline-none text-white placeholder:text-[#7070a0]"
              placeholder="Search by name, bio, city..."
            />
          </div>

          <div class="grid grid-cols-2 lg:flex gap-3">
            <select
              v-model="category"
              class="rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-[#c8ff00] outline-none"
            >
              <option value="" class="text-white bg-[#14142a]">All Categories</option>
              <option value="Tech" class="text-white bg-[#14142a]">Tech</option>
              <option value="Gadgets" class="text-white bg-[#14142a]">Gadgets</option>
              <option value="Fashion" class="text-white bg-[#14142a]">Fashion</option>
              <option value="Fitness" class="text-white bg-[#14142a]">Fitness</option>
              <option value="Food" class="text-white bg-[#14142a]">Food</option>
              <option value="Travel" class="text-white bg-[#14142a]">Travel</option>
            </select>

            <select
              v-model="location"
              class="rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-[#c8ff00] outline-none"
            >
              <option value="" class="text-white bg-[#14142a]">All Cities</option>
              <option value="Mumbai" class="text-white bg-[#14142a]">Mumbai</option>
              <option value="Delhi" class="text-white bg-[#14142a]">Delhi</option>
              <option value="Bangalore" class="text-white bg-[#14142a]">Bangalore</option>
              <option value="Hyderabad" class="text-white bg-[#14142a]">Hyderabad</option>
              <option value="Pune" class="text-white bg-[#14142a]">Pune</option>
            </select>

            <select
              v-model="platform"
              class="rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-[#c8ff00] outline-none"
            >
              <option value="" class="text-white bg-[#14142a]">All Platforms</option>
              <option value="instagram" class="text-white bg-[#14142a]">Instagram</option>
              <option value="youtube" class="text-white bg-[#14142a]">YouTube</option>
              <option value="x" class="text-white bg-[#14142a]">X</option>
              <option value="tiktok" class="text-white bg-[#14142a]">TikTok</option>
            </select>

            <button
              @click="applyFilters"
              class="rounded-xl bg-[#c8ff00] text-black font-semibold px-5 py-3 hover:scale-[1.02] transition"
            >
              Search
            </button>

            <button
              @click="resetFilters"
              class="rounded-xl border border-white/10 bg-white/5 text-white/80 px-5 py-3 hover:border-[#c8ff00]/40 hover:text-[#c8ff00] transition"
            >
              Reset
            </button>
          </div>
        </div>
      </div>

      <!-- results meta -->
      <div class="mt-6 flex items-center justify-between text-sm text-[#7070a0]">
        <div>
          Showing <span class="text-white font-semibold">{{ creators.length }}</span>
          of <span class="text-white font-semibold">{{ meta.total }}</span>
        </div>
      </div>

      <!-- cards -->
      <div v-if="creators.length === 0" class="mt-10 border border-white/10 bg-[#0e0e1a] rounded-2xl p-10 text-center">
        <div class="text-xl font-semibold">No creators found</div>
        <div class="text-[#7070a0] mt-2">Try changing filters or search query.</div>
      </div>

      <div v-else class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          v-for="c in creators"
          :key="c._id"
          class="group rounded-2xl border border-white/10 bg-[#05050a] p-6 hover:-translate-y-1 hover:border-[#c8ff00]/25 transition relative overflow-hidden"
        >
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition"
               style="background: linear-gradient(135deg, rgba(200,255,0,0.06), transparent);" />

          <div class="relative">
            <div class="flex items-start justify-between gap-3">
              <div class="w-14 h-14 rounded-2xl border border-white/10 bg-white/5 flex items-center justify-center text-2xl">
                {{ platformIcon(c.platforms?.[0]?.platform) }}
              </div>

              <div class="px-3 py-1 rounded-full text-xs font-semibold border border-[#7b5cff]/30 bg-[#7b5cff]/10 text-[#a78bfa]">
                ✓ Verified
              </div>
            </div>

            <div class="mt-4">
              <div class="text-lg font-semibold tracking-tight">
                {{ c.display_name }}
              </div>
              <div class="text-sm text-[#7070a0]">
                {{ (c.platforms?.[0]?.username ? '@' + c.platforms[0].username : '—') }} · {{ c.location || '—' }}
              </div>
            </div>

            <div class="mt-4 text-sm text-white/80 line-clamp-3">
              {{ c.bio || '—' }}
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
              <span
                v-for="(cat, idx) in (c.categories || []).slice(0, 3)"
                :key="idx"
                class="text-xs px-3 py-1 rounded-full bg-[#14142a] border border-white/10 text-[#7070a0]"
              >
                {{ cat }}
              </span>
            </div>

            <div class="mt-5 pt-5 border-t border-white/10 flex gap-6">
              <div>
                <div class="text-white font-bold">{{ formatFollowers(getMaxFollowers(c.platforms || [])) }}</div>
                <div class="text-xs text-[#7070a0]">Followers</div>
              </div>

              <div>
                <div class="text-white font-bold">
                  {{ getAvgEng(c.platforms || []) ? (getAvgEng(c.platforms || []) + '%') : '—' }}
                </div>
                <div class="text-xs text-[#7070a0]">Eng. Rate</div>
              </div>

              <div>
                <div class="text-white font-bold">₹{{ formatMoney(c.pricing?.post) }}</div>
                <div class="text-xs text-[#7070a0]">Post</div>
              </div>
            </div>

            <Link
              :href="`/app/creators/${c._id}`"
              class="mt-5 w-full inline-flex items-center justify-center rounded-xl border border-white/10 bg-[#0e0e1a] py-3 text-sm font-semibold text-white hover:bg-[#c8ff00] hover:text-black hover:border-[#c8ff00] transition"
            >
              View Profile →
            </Link>
          </div>
        </div>
      </div>
    </div>
  </MarketplaceLayout>
</template>
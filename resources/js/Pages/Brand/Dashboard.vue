<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import MarketplaceLayout from '@/Layouts/MarketplaceLayout.vue'

const page = usePage()

const creators = computed(() => page.props.creators || [])
const stats = computed(() => page.props.stats || {
  total_creators: 0,
  campaigns: 0,
  active_deals: 0
})

/* helpers (same as browse page) */

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

  <!-- HERO -->
  <div class="flex flex-col lg:flex-row gap-10 items-start lg:items-center justify-between">

    <div>

      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-[#c8ff00]/20 bg-[#c8ff00]/10 text-[#c8ff00] text-sm">
        <span class="w-2 h-2 rounded-full bg-[#c8ff00] animate-pulse"/>
        Brand dashboard
      </div>

      <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mt-4">
        Discover and hire <span class="text-transparent" style="-webkit-text-stroke: 1.2px #c8ff00;">creators</span>
      </h1>

      <p class="text-[#7070a0] mt-3 max-w-xl">
        Browse verified creators, view pricing, and launch campaigns instantly.
      </p>

      <div class="mt-6 flex gap-3">

        <Link
          href="/app/creators"
          class="rounded-xl bg-[#c8ff00] text-black font-semibold px-6 py-3 hover:scale-[1.02] transition"
        >
          Browse creators
        </Link>

        <Link
          href="/app/campaigns/create"
          class="rounded-xl border border-white/10 bg-white/5 text-white px-6 py-3 hover:border-[#c8ff00]/40 hover:text-[#c8ff00]"
        >
          Create campaign
        </Link>

      </div>

    </div>


    <!-- STATS -->
    <div class="grid grid-cols-3 gap-4 w-full max-w-md">

      <div class="rounded-2xl border border-white/10 bg-[#0e0e1a] p-5">
        <div class="text-2xl font-bold text-white">
          {{ stats.total_creators }}
        </div>
        <div class="text-sm text-[#7070a0]">Creators</div>
      </div>

      <div class="rounded-2xl border border-white/10 bg-[#0e0e1a] p-5">
        <div class="text-2xl font-bold text-white">
          {{ stats.campaigns }}
        </div>
        <div class="text-sm text-[#7070a0]">Campaigns</div>
      </div>

      <div class="rounded-2xl border border-white/10 bg-[#0e0e1a] p-5">
        <div class="text-2xl font-bold text-white">
          {{ stats.active_deals }}
        </div>
        <div class="text-sm text-[#7070a0]">Active</div>
      </div>

    </div>

  </div>



  <!-- FEATURED CREATORS -->
  <div class="mt-12">

    <div class="flex justify-between items-center">

      <h2 class="text-2xl font-bold">
        Featured creators
      </h2>

      <Link
        href="/app/creators"
        class="text-[#c8ff00]"
      >
        View all →
      </Link>

    </div>


    <!-- cards -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

      <div
        v-for="c in creators"
        :key="c._id"
        class="group rounded-2xl border border-white/10 bg-[#05050a] p-6 hover:-translate-y-1 hover:border-[#c8ff00]/25 transition relative overflow-hidden"
      >

        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition"
             style="background: linear-gradient(135deg, rgba(200,255,0,0.06), transparent);" />

        <div class="relative">

          <div class="flex items-start justify-between">

            <div class="w-14 h-14 rounded-2xl border border-white/10 bg-white/5 flex items-center justify-center text-2xl">
              {{ platformIcon(c.platforms?.[0]?.platform) }}
            </div>

            <div class="px-3 py-1 rounded-full text-xs font-semibold border border-[#7b5cff]/30 bg-[#7b5cff]/10 text-[#a78bfa]">
              ✓ Verified
            </div>

          </div>


          <div class="mt-4">

            <div class="text-lg font-semibold">
              {{ c.display_name }}
            </div>

            <div class="text-sm text-[#7070a0]">
              {{ c.location }}
            </div>

          </div>


          <div class="mt-4 flex gap-6">

            <div>
              <div class="font-bold">
                {{ formatFollowers(getMaxFollowers(c.platforms)) }}
              </div>
              <div class="text-xs text-[#7070a0]">Followers</div>
            </div>

            <div>
              <div class="font-bold">
                ₹{{ formatMoney(c.pricing?.post) }}
              </div>
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

</div>

</MarketplaceLayout>
</template>
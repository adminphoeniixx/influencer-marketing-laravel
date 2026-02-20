<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import MarketplaceLayout from '@/Layouts/MarketplaceLayout.vue'

const page = usePage()
const creator = computed(() => page.props.creator || {})

const money = (n) => Number(n || 0).toLocaleString('en-IN')

const icon = (p) => {
  const n = (p || '').toLowerCase()
  if (n === 'instagram') return '📸'
  if (n === 'youtube') return '▶️'
  if (n === 'tiktok') return '🎵'
  if (n === 'twitter' || n === 'x') return '𝕏'
  return '✨'
}
</script>

<template>
  <MarketplaceLayout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
      <Link href="/app/creators" class="text-sm text-[#7070a0] hover:text-white transition">
        ← Back to creators
      </Link>

      <div class="mt-6 border border-white/10 bg-[#0e0e1a] rounded-3xl p-6 sm:p-10">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
          <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-2xl border border-white/10 bg-white/5 flex items-center justify-center text-3xl">
              {{ icon(creator.platforms?.[0]?.platform) }}
            </div>

            <div>
              <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                {{ creator.display_name }}
              </h1>
              <div class="text-[#7070a0] mt-1">
                {{ creator.location || '—' }}
              </div>

              <div class="mt-3 flex flex-wrap gap-2">
                <span
                  v-for="(cat, idx) in (creator.categories || [])"
                  :key="idx"
                  class="text-xs px-3 py-1 rounded-full bg-[#14142a] border border-white/10 text-[#7070a0]"
                >
                  {{ cat }}
                </span>
              </div>
            </div>
          </div>

          <div class="self-start px-3 py-1 rounded-full text-xs font-semibold border border-[#7b5cff]/30 bg-[#7b5cff]/10 text-[#a78bfa]">
            ✓ Approved
          </div>
        </div>

        <p class="mt-6 text-white/85 leading-relaxed">
          {{ creator.bio || '—' }}
        </p>

        <!-- Platforms -->
        <div class="mt-10">
          <div class="text-sm font-semibold mb-3">Platforms</div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div
              v-for="p in (creator.platforms || [])"
              :key="p.platform + ':' + p.username"
              class="rounded-2xl border border-white/10 bg-black/20 p-4"
            >
              <div class="flex items-center justify-between">
                <div class="font-semibold">
                  <span class="mr-2">{{ icon(p.platform) }}</span>
                  {{ p.platform }}
                </div>
                <div class="text-sm text-[#c8ff00] font-semibold">
                  {{ Number(p.followers || 0).toLocaleString('en-IN') }} followers
                </div>
              </div>

              <div class="text-[#7070a0] text-sm mt-1">
                @{{ p.username || '—' }}
              </div>

              <div class="text-[#7070a0] text-sm mt-2">
                Engagement: <span class="text-white">{{ p.engagement ?? '—' }}</span>
              </div>

              <div v-if="p.services" class="mt-3 text-sm">
                <div class="text-[#7070a0]">Services</div>
                <div class="mt-1 flex flex-wrap gap-2">
                  <span
                    v-for="(val, key) in p.services"
                    :key="key"
                    class="text-xs px-3 py-1 rounded-full border border-white/10 bg-white/5 text-white/80"
                  >
                    {{ key }}: ₹{{ money(val) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pricing -->
        <div class="mt-10">
          <div class="text-sm font-semibold mb-3">Base Pricing</div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
              <div class="text-xs text-[#7070a0]">Post</div>
              <div class="text-2xl font-extrabold text-white mt-1">₹{{ money(creator.pricing?.post) }}</div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
              <div class="text-xs text-[#7070a0]">Story</div>
              <div class="text-2xl font-extrabold text-white mt-1">₹{{ money(creator.pricing?.story) }}</div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
              <div class="text-xs text-[#7070a0]">Reel</div>
              <div class="text-2xl font-extrabold text-white mt-1">₹{{ money(creator.pricing?.reel) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MarketplaceLayout>
</template>
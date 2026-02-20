<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user || null)

const mobileOpen = ref(false)

const roles = computed(() => {
  const raw = user.value?.roles || []
  // spatie can be array of objects or strings
  return raw.map(r => (typeof r === 'string' ? r : r.name)).filter(Boolean)
})

const isBrand = computed(() => roles.value.includes('brand'))
const isCreator = computed(() => roles.value.includes('creator'))

const navLinks = computed(() => {
  const common = [
    { label: 'Discover', href: '/app/creators' },
  ]

  if (!user.value) {
    return common
  }

  if (isBrand.value) {
    return [
      ...common,
      { label: 'Dashboard', href: '/app/brand/dashboard-view' },
    ]
  }

  if (isCreator.value) {
    return [
      { label: 'Dashboard', href: '/app/creator/dashboard-view' },
      { label: 'My Profile', href: '/app/creator/profile-view' },
    ]
  }

  return common
})

const appLogo = computed(() => 'Creator#Link') // change later if needed
</script>

<template>
  <div class="min-h-screen bg-[#05050a] text-[#f0f0f8] relative overflow-x-hidden">
    <!-- subtle noise-ish overlay -->
    <div
      class="pointer-events-none fixed inset-0 opacity-40"
      style="background-image: radial-gradient(rgba(200,255,0,0.08) 1px, transparent 1px); background-size: 24px 24px;"
    />

    <!-- NAV -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-[#05050a]/70 border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
        <Link href="/" class="font-extrabold tracking-tight text-lg sm:text-xl">
          <span>{{ appLogo.split('#')[0] }}</span><span class="text-[#c8ff00]">#</span><span>{{ appLogo.split('#')[1] }}</span>
        </Link>

        <!-- desktop links -->
        <nav class="hidden md:flex items-center gap-8">
          <Link
            v-for="l in navLinks"
            :key="l.href"
            :href="l.href"
            class="text-sm font-medium text-[#7070a0] hover:text-white transition"
          >
            {{ l.label }}
          </Link>
        </nav>

        <div class="flex items-center gap-3">
          <!-- auth CTAs -->
          <template v-if="!user">
            <Link
              href="/login"
              class="hidden sm:inline-flex items-center px-4 py-2 rounded-full border border-white/10 hover:border-[#c8ff00]/60 hover:text-[#c8ff00] transition text-sm"
            >
              Log in
            </Link>
            <Link
              href="/register"
              class="inline-flex items-center px-4 py-2 rounded-full bg-[#c8ff00] text-black font-semibold hover:scale-[1.02] transition text-sm"
            >
              Get Access
            </Link>
          </template>

          <!-- user badge -->
          <template v-else>
            <div class="hidden sm:flex items-center gap-2 text-sm text-white/80">
              <span class="px-3 py-1 rounded-full border border-white/10 bg-white/5">
                Hi, <span class="text-white font-semibold">{{ user.name }}</span>
              </span>
            </div>
          </template>

          <!-- mobile menu button -->
          <button
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl border border-white/10 bg-white/5"
            @click="mobileOpen = !mobileOpen"
            aria-label="Toggle menu"
          >
            <span v-if="!mobileOpen">☰</span>
            <span v-else>✕</span>
          </button>
        </div>
      </div>

      <!-- mobile links -->
      <div v-if="mobileOpen" class="md:hidden border-t border-white/10 bg-[#05050a]/90">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-3">
          <Link
            v-for="l in navLinks"
            :key="l.href"
            :href="l.href"
            class="text-sm font-medium text-[#7070a0] hover:text-white transition"
            @click="mobileOpen = false"
          >
            {{ l.label }}
          </Link>

          <template v-if="!user">
            <div class="pt-2 flex gap-3">
              <Link
                href="/login"
                class="flex-1 text-center px-4 py-2 rounded-full border border-white/10 hover:border-[#c8ff00]/60 hover:text-[#c8ff00] transition text-sm"
                @click="mobileOpen = false"
              >
                Log in
              </Link>
              <Link
                href="/register"
                class="flex-1 text-center px-4 py-2 rounded-full bg-[#c8ff00] text-black font-semibold transition text-sm"
                @click="mobileOpen = false"
              >
                Get Access
              </Link>
            </div>
          </template>
        </div>
      </div>
    </header>

    <!-- Page Glow -->
    <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[520px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(200,255,0,0.10) 0%, transparent 70%);" />
    <div class="pointer-events-none absolute top-32 left-10 w-[420px] h-[420px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(123,92,255,0.12) 0%, transparent 70%);" />

    <!-- CONTENT -->
    <main class="relative z-10">
      <slot />
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 border-t border-white/10 mt-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="font-bold">
          Creator<span class="text-[#c8ff00]">#</span>Link
        </div>

        <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-[#7070a0]">
          <a class="hover:text-white transition" href="#">Privacy</a>
          <a class="hover:text-white transition" href="#">Terms</a>
          <a class="hover:text-white transition" href="#">About</a>
          <a class="hover:text-white transition" href="#">Contact</a>
        </div>

        <div class="text-xs text-[#7070a0]">© 2026 CreatorLink. Built for signal.</div>
      </div>
    </footer>
  </div>
</template>
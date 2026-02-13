<script setup>
import { ref, onMounted, watch } from 'vue'
import SidebarLink from '@/Components/SidebarLink.vue'
import LogoutButton from '@/Components/LogoutButton.vue'

const collapsed = ref(false)
const mobileOpen = ref(false)

onMounted(() => {
  collapsed.value = localStorage.getItem('admin_sidebar') === 'collapsed'
})

watch(mobileOpen, (val) => {
  document.body.style.overflow = val ? 'hidden' : ''
})

const toggleSidebar = () => {
  collapsed.value = !collapsed.value
  localStorage.setItem(
    'admin_sidebar',
    collapsed.value ? 'collapsed' : 'expanded'
  )
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Mobile overlay -->
    <div
      v-if="mobileOpen"
      class="fixed inset-0 bg-black/50 z-40 md:hidden"
      @click="mobileOpen = false"
    />

    <!-- Sidebar -->
    <aside
      class="fixed md:static z-50 bg-gray-900 text-white flex flex-col transition-all duration-300
             md:translate-x-0"
      :class="[
        mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        collapsed ? 'md:w-20' : 'md:w-64',
        'w-64'
      ]"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-4">
        <span v-if="!collapsed" class="font-bold">Admin Panel</span>

        <button
          class="hidden md:block text-lg"
          @click="toggleSidebar"
        >
          {{ collapsed ? '➡️' : '⬅️' }}
        </button>

        <button
          class="md:hidden text-lg"
          @click="mobileOpen = false"
        >
          ✖
        </button>
      </div>

      <!-- Menu -->
      <nav class="flex-1 space-y-1">
        <SidebarLink href="/admin/dashboard" icon="📊" :collapsed="collapsed">
          Dashboard
        </SidebarLink>

        <SidebarLink href="/admin/creators" icon="🧑‍🎤" :collapsed="collapsed">
          Creators
        </SidebarLink>

        <SidebarLink href="/admin/brands" icon="🏢" :collapsed="collapsed">
          Brands
        </SidebarLink>

        <SidebarLink href="/admin/settings" icon="⚙️" :collapsed="collapsed">
          Settings
        </SidebarLink>
      </nav>

      <div class="p-3 border-t border-gray-200">
        <LogoutButton :collapsed="collapsed" />
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 md:ml-0">
      <!-- Mobile top bar -->
      <div class="md:hidden flex items-center p-4 bg-white shadow">
        <button @click="mobileOpen = true" class="text-xl">☰</button>
        <span class="ml-4 font-semibold">Admin Panel</span>
      </div>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
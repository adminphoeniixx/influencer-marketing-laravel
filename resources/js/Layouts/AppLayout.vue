<script setup>
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SidebarLink from '@/Components/SidebarLink.vue'
import LogoutButton from '@/Components/LogoutButton.vue'

const page = usePage()

/* ✅ Normalize roles (Spatie format safe) */
const roles = computed(() =>
  (page.props.auth.user.roles || []).map(r => r.name)
)

const collapsed = ref(false)

onMounted(() => {
  collapsed.value = localStorage.getItem('app_sidebar') === 'collapsed'
})

const toggleSidebar = () => {
  collapsed.value = !collapsed.value
  localStorage.setItem(
    'app_sidebar',
    collapsed.value ? 'collapsed' : 'expanded'
  )
}

/* ✅ SINGLE SOURCE OF TRUTH FOR DASHBOARD */
const dashboardUrl = computed(() => {
  if (roles.value.includes('creator')) {
    return '/app/creator/dashboard-view'
  }

  if (roles.value.includes('brand')) {
    return '/app/brand/dashboard-view'
  }

  return '/dashboard' // admin handled via AdminLayout
})
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside
      class="bg-white border-r flex flex-col transition-all duration-300"
      :class="collapsed ? 'w-20' : 'w-64'"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-4">
        <span v-if="!collapsed" class="font-bold text-gray-900">
          Influencer Platform
        </span>
        <button @click="toggleSidebar" class="text-xl">
          {{ collapsed ? '➡️' : '⬅️' }}
        </button>
      </div>

      <!-- Menu -->
      <nav class="flex-1 space-y-1">
        <!-- DASHBOARD -->
        <SidebarLink
          :href="dashboardUrl"
          icon="🏠"
          :collapsed="collapsed"
        >
          Dashboard
        </SidebarLink>

        <!-- CREATOR LINKS -->
        <template v-if="roles.includes('creator')">
          <SidebarLink
            href="/app/creator/profile-view"
            icon="👤"
            :collapsed="collapsed"
          >
            My Profile
          </SidebarLink>

          <SidebarLink
            href="/app/campaigns"
            icon="📢"
            :collapsed="collapsed"
          >
            Campaigns
          </SidebarLink>

          <SidebarLink
            href="/app/earnings"
            icon="💰"
            :collapsed="collapsed"
          >
            Earnings
          </SidebarLink>
        </template>

        <!-- BRAND LINKS -->
        <!-- BRAND LINKS -->
      <template v-if="roles.includes('brand')">

        <SidebarLink
          href="/app/campaigns/create"
          icon="➕"
          :collapsed="collapsed"
        >
          Create Campaign
        </SidebarLink>

        <SidebarLink
          href="/app/creators-view"
          icon="🔍"
          :collapsed="collapsed"
        >
          Find Creators
        </SidebarLink>

        <SidebarLink
          href="/app/shortlist"
          icon="⭐"
          :collapsed="collapsed"
        >
          Shortlist
        </SidebarLink>

      </template>

        
      </nav>

      <!-- Logout -->
      <div class="p-3 border-t border-gray-200">
        <LogoutButton :collapsed="collapsed" />
      </div>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6">
      <slot />
    </main>
  </div>
</template>
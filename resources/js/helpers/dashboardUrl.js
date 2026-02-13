import { usePage } from '@inertiajs/vue3'

export function getDashboardUrl() {
  const user = usePage().props.auth.user

  if (!user) return '/login'

  if (user.roles.includes('creator')) {
    return '/app/creator/dashboard-view'
  }

  if (user.roles.includes('brand')) {
    return '/app/brand/dashboard'
  }

  if (user.roles.includes('admin') || user.roles.includes('sub_admin')) {
    return '/admin/dashboard'
  }

  return '/dashboard'
}
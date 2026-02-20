<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.transform((data) => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Login" />

  <div class="topbar">
    <div class="container">
      <div class="nav">
        <Link href="/" class="brand">
          <span class="brand-badge"></span>
          Influencer Platform
        </Link>

        <div class="cta-row">
          <Link class="btn btn-ghost" :href="route('register')">Create account</Link>
        </div>
      </div>
    </div>
  </div>

  <div class="hf-auth-wrap hero">
    <div class="container">
      <div class="hero-grid">
        <!-- Left -->
        <div>
          <div class="kicker">
            <span class="kicker-dot"></span>
            Welcome back
          </div>

          <h1 class="h1">
            Login and <span class="glow">start matching</span> with creators
          </h1>

          <p class="sub">
            Brands discover creators. Creators get campaigns. Everything in one place.
          </p>

          <div v-if="status" class="card" style="margin-top: 14px;">
            <div class="muted">{{ status }}</div>
          </div>
        </div>

        <!-- Right: Login card -->
        <div class="card hf-auth-card">
          <div class="card-title">Login</div>
          <div class="muted">Enter your email and password.</div>

          <div class="divider"></div>

          <form @submit.prevent="submit">
            <div class="hf-field">
              <label class="hf-label" for="email">Email</label>
              <input
                id="email"
                v-model="form.email"
                class="hf-input"
                type="email"
                autocomplete="username"
                required
                autofocus
              />
              <div v-if="form.errors.email" class="hf-error">{{ form.errors.email }}</div>
            </div>

            <div class="hf-field">
              <label class="hf-label" for="password">Password</label>
              <input
                id="password"
                v-model="form.password"
                class="hf-input"
                type="password"
                autocomplete="current-password"
                required
              />
              <div v-if="form.errors.password" class="hf-error">{{ form.errors.password }}</div>
            </div>

            <div class="hf-row">
              <label class="muted" style="display:flex; gap:10px; align-items:center;">
                <input type="checkbox" v-model="form.remember" />
                Remember me
              </label>

              <Link
                v-if="canResetPassword"
                class="hf-link"
                :href="route('password.request')"
              >
                Forgot password?
              </Link>
            </div>

            <div class="hf-row" style="justify-content: flex-end;">
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
                :style="form.processing ? 'opacity:.7; cursor:not-allowed;' : ''"
              >
                {{ form.processing ? 'Signing in...' : 'Login' }}
              </button>
            </div>

            <div class="muted" style="margin-top: 14px;">
              Don’t have an account?
              <Link class="hf-link" :href="route('register')">Register</Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
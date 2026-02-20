<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

// allow /register?role=brand or /register?role=creator
const roleFromQuery = computed(() => {
  const q = page.url.includes('?') ? page.url.split('?')[1] : ''
  const params = new URLSearchParams(q)
  const r = params.get('role')
  return r === 'brand' || r === 'creator' ? r : 'creator'
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: roleFromQuery.value, // ✅ NEW
  terms: false,
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Register" />

  <div class="topbar">
    <div class="container">
      <div class="nav">
        <Link href="/" class="brand">
          <span class="brand-badge"></span>
          Influencer Platform
        </Link>

        <div class="cta-row">
          <Link class="btn btn-ghost" :href="route('login')">Login</Link>
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
            Create your account
          </div>

          <h1 class="h1">
            Join as a <span class="glow">Brand</span> or <span class="glow">Creator</span>
          </h1>

          <p class="sub">
            Brands find creators fast. Creators get discovered, verified, and booked.
          </p>
        </div>

        <!-- Right: Register card -->
        <div class="card hf-auth-card">
          <div class="card-title">Register</div>
          <div class="muted">Choose your role and fill your details.</div>

          <div class="divider"></div>

          <form @submit.prevent="submit">
            <!-- Role -->
            <div>
              <div class="hf-label">I am a</div>
              <div class="hf-pill-row">
                <label class="hf-pill" :class="{ active: form.role === 'creator' }">
                  <input type="radio" value="creator" v-model="form.role" />
                  Creator
                </label>

                <label class="hf-pill" :class="{ active: form.role === 'brand' }">
                  <input type="radio" value="brand" v-model="form.role" />
                  Brand
                </label>
              </div>
              <div v-if="form.errors.role" class="hf-error">{{ form.errors.role }}</div>
            </div>

            <div class="hf-field">
              <label class="hf-label" for="name">Name</label>
              <input
                id="name"
                v-model="form.name"
                class="hf-input"
                type="text"
                autocomplete="name"
                required
                autofocus
              />
              <div v-if="form.errors.name" class="hf-error">{{ form.errors.name }}</div>
            </div>

            <div class="hf-field">
              <label class="hf-label" for="email">Email</label>
              <input
                id="email"
                v-model="form.email"
                class="hf-input"
                type="email"
                autocomplete="username"
                required
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
                autocomplete="new-password"
                required
              />
              <div v-if="form.errors.password" class="hf-error">{{ form.errors.password }}</div>
            </div>

            <div class="hf-field">
              <label class="hf-label" for="password_confirmation">Confirm password</label>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                class="hf-input"
                type="password"
                autocomplete="new-password"
                required
              />
              <div v-if="form.errors.password_confirmation" class="hf-error">
                {{ form.errors.password_confirmation }}
              </div>
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="hf-field">
              <label class="muted" style="display:flex; gap:10px; align-items:flex-start;">
                <input type="checkbox" v-model="form.terms" />
                <span>
                  I agree to the
                  <a class="hf-link" target="_blank" :href="route('terms.show')">Terms</a>
                  and
                  <a class="hf-link" target="_blank" :href="route('policy.show')">Privacy Policy</a>.
                </span>
              </label>
              <div v-if="form.errors.terms" class="hf-error">{{ form.errors.terms }}</div>
            </div>

            <div class="hf-row" style="justify-content: space-between;">
              <Link class="hf-link" :href="route('login')">Already registered?</Link>

              <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
                :style="form.processing ? 'opacity:.7; cursor:not-allowed;' : ''"
              >
                {{ form.processing ? 'Creating...' : 'Register' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
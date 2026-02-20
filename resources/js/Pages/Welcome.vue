<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
})
</script>

<template>
  <Head title="Home" />

  <div class="topbar">
    <div class="container">
      <div class="nav">
        <Link href="/" class="brand">
          <span class="brand-badge"></span>
          Influencer Platform
        </Link>

        <div class="navlinks">
          <a href="#how">How it works</a>
          <a href="#features">Features</a>
        </div>

        <div class="cta-row">
          <Link v-if="canLogin" class="btn btn-ghost" :href="route('login')">Login</Link>
          <Link v-if="canRegister" class="btn btn-primary" :href="route('register')">Get started</Link>
        </div>
      </div>
    </div>
  </div>

  <section class="hero">
    <div class="container">
      <div class="hero-grid">
        <div>
          <div class="kicker">
            <span class="kicker-dot"></span>
            HashFame-style influencer discovery & booking
          </div>

          <h1 class="h1">
            Find creators that <span class="glow">actually convert</span>
          </h1>

          <p class="sub">
            Brands can filter creators by platform, category, location, language, age group and pricing.
            Creators get verified and receive campaign invites.
          </p>

          <div class="cta-row" style="margin-top: 16px;">
            <Link class="btn btn-primary" :href="route('register') + '?role=brand'">
              Join as Brand
            </Link>
            <Link class="btn btn-ghost" :href="route('register') + '?role=creator'">
              Join as Creator
            </Link>
          </div>

          <div class="muted" style="margin-top: 18px;">
            Already have an account?
            <Link class="hf-link" :href="route('login')">Login</Link>
          </div>
        </div>

        <div class="card">
          <div class="card-title">What you get</div>

          <div class="divider"></div>

          <div class="muted" style="line-height:1.7;">
            ✅ Verified creator profiles<br />
            ✅ Platform-wise follower counts & links<br />
            ✅ Category + location targeting<br />
            ✅ Fast browsing with MongoDB + Redis caching<br />
            ✅ Campaign pipeline (coming next)
          </div>

          <div class="divider"></div>

          <div class="cta-row">
            <Link class="btn btn-primary" :href="route('register')">
              Create account
            </Link>
            <Link class="btn btn-ghost" :href="route('login')">
              Login
            </Link>
          </div>
        </div>
      </div>

      <div id="how" class="card" style="margin-top: 28px;">
        <div class="card-title">How it works</div>
        <div class="muted" style="line-height:1.7;">
          1) Creator registers → builds profile → submits for review.<br/>
          2) Admin approves → creator becomes visible to brands.<br/>
          3) Brand browses creators → shortlists → creates campaign → invites creators.
        </div>
      </div>

      <div id="features" class="card" style="margin-top: 16px;">
        <div class="card-title">Features</div>
        <div class="muted" style="line-height:1.7;">
          Filters, profiles, pricing, multiple platforms per creator, and a clean booking flow.
        </div>
      </div>
    </div>
  </section>
</template>
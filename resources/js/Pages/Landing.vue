<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Influencer Platform" />

  <!-- TOPBAR (same as login) -->
  <div class="topbar">
    <div class="container">
      <div class="nav">

        <Link href="/" class="brand">
          <span class="brand-badge"></span>
          Influencer Platform
        </Link>

        <div class="cta-row">

          <Link href="/discover" class="btn btn-ghost">
            Discover
          </Link>

          <Link href="/register?role=brand" class="btn btn-ghost">
            For Brands
          </Link>

          <Link href="/register?role=creator" class="btn btn-ghost">
            For Creators
          </Link>

          <Link href="/login" class="btn btn-ghost">
            Log in
          </Link>

          <Link href="/register" class="btn btn-primary">
            Get Access
          </Link>

        </div>

      </div>
    </div>
  </div>


  <!-- HERO SECTION (same layout as login left side) -->
  <div class="hf-auth-wrap hero">

    <div class="container">

      <div class="hero-grid">

        <!-- LEFT CONTENT -->
        <div>

          <div class="kicker">
            <span class="kicker-dot"></span>
            Verified Creators · No Middlemen
          </div>

          <h1 class="h1">
            Where Brands <br>
            <span class="glow">Meet Creators</span>
          </h1>

          <p class="sub">
            Discover influencers. Launch campaigns. Grow your brand faster.
          </p>

          <div style="margin-top: 24px; display:flex; gap:14px;">

            <Link
              href="/register?role=brand"
              class="btn btn-primary"
            >
              Join as Brand →
            </Link>

            <Link
              href="/register?role=creator"
              class="btn btn-ghost"
            >
              I’m Creator
            </Link>

          </div>

        </div>


        <!-- RIGHT SIDE CARD -->
        <div class="card hf-auth-card">

          <div class="card-title">
            Start in seconds
          </div>

          <div class="muted">
            Join thousands of creators and brands already using our platform.
          </div>

          <div class="divider"></div>

          <div style="display:flex; flex-direction:column; gap:12px;">

            <Link
              href="/register?role=brand"
              class="btn btn-primary"
            >
              Join as Brand
            </Link>

            <Link
              href="/register?role=creator"
              class="btn btn-ghost"
            >
              Join as Creator
            </Link>

            <Link
              href="/login"
              class="btn btn-ghost"
            >
              Already have account? Login
            </Link>

          </div>

        </div>


      </div>

    </div>

  </div>

</template>
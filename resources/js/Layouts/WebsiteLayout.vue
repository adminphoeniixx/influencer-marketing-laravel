<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const user = page.props.auth?.user ?? null
</script>

<template>

<div>

    <!-- NAV (EXACT SAME) -->
    <nav>
        <div class="nav-logo">
            Creator<span>#</span>Link
        </div>

        <ul class="nav-links">
            <li><Link href="/">Discover</Link></li>
            <li><Link href="/register">For Brands</Link></li>
            <li><Link href="/register">For Creators</Link></li>
        </ul>

        <div class="nav-ctas">

            <Link
                v-if="!user"
                href="/login"
                class="btn-ghost"
            >
                Log in
            </Link>

            <Link
                v-if="!user"
                href="/register"
                class="btn-primary"
            >
                Get Access
            </Link>

            <Link
                v-if="user"
                href="/dashboard"
                class="btn-primary"
            >
                Dashboard
            </Link>

        </div>
    </nav>


    <!-- CONTENT -->
    <slot />


    <!-- FOOTER (EXACT SAME) -->
    <footer>

        <div class="footer-logo">
            Creator<span>#</span>Link
        </div>

        <div class="footer-links">

            <Link href="#">Privacy</Link>
            <Link href="#">Terms</Link>
            <Link href="#">About</Link>

        </div>

        <div>
            © 2026 CreatorLink
        </div>

    </footer>

</div>

</template>
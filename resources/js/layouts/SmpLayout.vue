<script setup lang="ts">
import '@fontsource-variable/archivo/wdth.css';
import '@fontsource/atkinson-hyperlegible/400.css';
import '@fontsource/atkinson-hyperlegible/700.css';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{ siteName: string; companyName: string }>();

const page = usePage();
const user = computed(() => (page.props.auth as any)?.user ?? null);

const allLinks = [
    { href: '/admin', label: 'Administrator', staff: true },
    { href: '/history', label: 'History', staff: false },
    { href: '/design/sato', label: 'Design SATO Label', staff: true },
    { href: '/design/tformer', label: 'Upload TFORMer Design', staff: true },
];
const links = computed(() => allLinks.filter((l) => !l.staff || user.value));
const isActive = (href: string) => page.url === href || page.url.startsWith(href + '/');
</script>

<template>
    <div class="smp-app">
        <header class="smp-bar">
            <Link href="/" class="smp-brand">
                <img src="/images/icon.png" :alt="`${companyName} logo`" class="smp-logo-img" />
                <span>{{ siteName }}</span>
            </Link>
            <nav class="smp-nav" aria-label="Main">
                <Link v-for="l in links" :key="l.href" :href="l.href" class="smp-nav__link" :class="{ 'is-active': isActive(l.href) }">
                    {{ l.label }}
                </Link>
            </nav>
            <div class="smp-account">
                <template v-if="user">
                    <span>{{ user.name }}</span>
                    <Link href="/logout" method="post" as="button" class="smp-btn smp-btn--ghost">Log out</Link>
                </template>
                <Link v-else href="/login" class="smp-btn">Login</Link>
            </div>
        </header>
        <main class="smp-main"><slot /></main>
        <footer class="smp-foot">
            <span class="smp-hero__brand2">
                <img src="/images/smp.ico" :alt="`${companyName} logo`" class="smp-logo-img-footer" />
                <span class="">{{ companyName }}</span>
            </span>
        </footer>

    </div>
</template>

<style>
:root { --blue: #0068cf; --cyan: #00cfcf; --navy: #062a52; --liner: #e6eff8; --edge: #b7cce3; --muted: #4a627c; }
.smp-app {
    min-height: 100vh; display: flex; flex-direction: column; color: var(--navy);
    font: 400 1.1875rem/1.5 'Atkinson Hyperlegible', sans-serif;
    background:
        radial-gradient(1100px 480px at 95% -8%, rgba(0, 207, 207, 0.3), transparent 62%),
        radial-gradient(circle at 1px 1px, rgba(0, 104, 207, 0.17) 1.5px, transparent 1.6px) 0 0 / 28px 28px,
        var(--liner);
}
.smp-app *, .smp-app *::before, .smp-app *::after { box-sizing: border-box; }
.smp-app a { color: inherit; text-decoration: none; }
.smp-app :focus-visible { outline: 4px solid var(--navy); outline-offset: 3px; box-shadow: 0 0 0 7px var(--cyan); }
.smp-bar { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem 1.5rem; padding: 0.75rem clamp(1rem, 4vw, 3rem); background: var(--blue); color: #fff; }
.smp-brand { display: flex; align-items: center; gap: 0.75rem; margin-right: auto; font: 800 1.25rem/1 'Archivo Variable', sans-serif; font-stretch: 120%; }
.smp-logo-img { height: 3rem; width: auto; max-width: 11rem; padding: 0.35rem 0.6rem; background: #fff; border-radius: 0.75rem; object-fit: contain; }
.smp-nav { display: flex; flex-wrap: wrap; gap: 0.25rem; }
.smp-nav__link { display: flex; align-items: center; min-height: 48px; padding: 0.5rem 1.1rem; border-radius: 0.75rem; font-weight: 700; }
.smp-nav__link:hover { background: rgba(255, 255, 255, 0.18); }
.smp-nav__link.is-active { background: #fff; color: var(--blue); }
.smp-account { display: flex; align-items: center; gap: 0.75rem; }
.smp-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 0.5rem 1.4rem; border: 0; border-radius: 0.75rem; background: var(--cyan); color: var(--navy); font: 700 1.05rem 'Atkinson Hyperlegible', sans-serif; cursor: pointer; }
.smp-btn--ghost { background: transparent; color: #fff; box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.75); }
.smp-main { flex: 1; width: min(1200px, 100%); margin: 0 auto; padding: clamp(2rem, 6vw, 4.5rem) clamp(1rem, 4vw, 3rem); }
.smp-foot { padding: 1.5rem; text-align: center; color: var(--muted); font-size: 1rem; }
.smp-hero__brand2 { display: flex; align-items: center; gap: 0.6rem; justify-content: center; }
.smp-logo-img-footer { height: 2rem; width: auto; max-width: 11rem; padding: 0.35rem 0.6rem; border-radius: 0.75rem; object-fit: contain; }
@media (max-width: 1100px) { .smp-nav { order: 3; flex-basis: 100%; } }
@media (prefers-reduced-motion: reduce) { .smp-app *, .smp-app *::before, .smp-app *::after { animation: none !important; transition: none !important; } }
</style>

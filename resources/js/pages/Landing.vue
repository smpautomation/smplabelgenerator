<script setup lang="ts">
import SmpLayout from '@/layouts/SmpLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Option = { type: string; label: string; enabled: boolean };
type Customer = { name: string; slug: string; logo: string | null; options: Option[] };

const props = defineProps<{ customers: Customer[]; brand: { company_name: string; site_name: string } }>();

const query = ref('');
const openSlug = ref<string | null>(null); // one card open at a time keeps tablet screens tidy

const shown = computed(() => {
    const q = query.value.trim().toLowerCase();
    return q ? props.customers.filter((c) => c.name.toLowerCase().includes(q)) : props.customers;
});
const toggle = (slug: string) => (openSlug.value = openSlug.value === slug ? null : slug);
const initials = (n: string) => n.split(/\s+/).filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase();

// Inner SVG paths, 24x24 stroke icons
const icons: Record<string, string> = {
    tray: '<path d="M3 12h18l-2 6H5l-2-6z"/><path d="M6 12V8m12 4V8"/>',
    package: '<rect x="4" y="8" width="16" height="11" rx="2"/><path d="M4 12h16M10 8V5h4v3"/>',
    box: '<path d="M4 8l8-4 8 4v8l-8 4-8-4V8z"/><path d="M4 8l8 4 8-4M12 12v8"/>',
    pallet: '<rect x="6" y="3" width="5" height="5"/><rect x="13" y="3" width="5" height="5"/><path d="M3 11h18v3H3zM5 14v4m7-4v4m7-4v4"/>',
};
</script>

<template>
    <Head :title="brand.site_name" />
    <SmpLayout :site-name="brand.site_name" :company-name="brand.company_name">
        <section class="smp-hero">
            <h1 class="smp-hero__title">
                <span class="smp-hero__brand">
                    <img src="/images/smp.ico" :alt="`${companyName} logo`" class="smp-logo-img2" />
                    <span class="smp-hero__co">{{ brand.company_name }}</span>
                </span>
                <span class="smp-hero__site">{{ brand.site_name }}</span>
            </h1>
            <p class="smp-hero__lead">Print all the sticker labels you need, right here on this website.</p>
            <label class="smp-find">
                Find a customer
                <input v-model="query" type="search" placeholder="Type a customer name" autocomplete="off" />
            </label>
        </section>

        <ul v-if="shown.length" class="smp-grid">
            <li v-for="(c, i) in shown" :key="c.slug" class="smp-card" :class="{ 'is-open': openSlug === c.slug }" :style="{ '--i': i }">
                <button type="button" class="smp-card__head" :aria-expanded="openSlug === c.slug" :aria-controls="`opts-${c.slug}`" @click="toggle(c.slug)">
                    <span class="smp-logo">
                        <img v-if="c.logo" :src="c.logo" :alt="`${c.name} logo`" />
                        <span v-else class="smp-logo__mono" :class="{ alt: i % 2 }" aria-hidden="true">{{ initials(c.name) }}</span>
                    </span>
                    <span class="smp-card__name">{{ c.name }}</span>
                    <span class="smp-card__hint">{{ openSlug === c.slug ? 'Tap again to close' : 'Tap to choose a label' }}</span>
                </button>
                <div :id="`opts-${c.slug}`" class="smp-card__panel">
                    <div class="smp-card__clip">
                        <ul class="smp-opts">
                            <li v-for="o in c.options" :key="o.type">
                                <component :is="o.enabled ? Link : 'span'" v-bind="o.enabled ? { href: `/labels/${c.slug}/${o.type}` } : { 'aria-disabled': 'true' }" class="smp-opt" :class="{ 'is-off': !o.enabled }">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" v-html="icons[o.type]" />
                                    <span class="smp-opt__name">{{ o.label }}</span>
                                    <small v-if="!o.enabled">Not available</small>
                                </component>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>

        <p v-else class="smp-empty">
            <template v-if="customers.length">
                No customer matches “{{ query }}”. Check the spelling, or
                <button type="button" class="smp-link" @click="query = ''">show all customers</button>.
            </template>
            <template v-else>No customers have been added yet. Staff can add them from Administrator.</template>
        </p>
    </SmpLayout>
</template>

<style>
.smp-hero__title { margin: 0; display: flex; flex-direction: column; gap: 0.6rem; font-family: 'Archivo Variable', sans-serif; }
.smp-hero__brand {
    display: flex;
    align-items: center;   /* vertically center logo with text */
    gap: 0.6rem;           /* spacing between logo and name */
}
.smp-hero__co { font-size: clamp(1.25rem, 2.4vw, 1.75rem); font-weight: 700; font-stretch: 110%; }
.smp-logo-img2 { height: 3rem; width: auto; max-width: 11rem; padding: 0.35rem 0.6rem; border-radius: 0.75rem; object-fit: contain; }
.smp-hero__site { font-size: clamp(2.6rem, 7.5vw, 5.6rem); font-weight: 800; font-stretch: 125%; line-height: 0.95; letter-spacing: -0.02em; color: var(--blue); }
.smp-hero__lead { max-width: 30rem; margin: 1.25rem 0 0; font-size: 1.4rem; line-height: 1.4; }
.smp-find { display: block; max-width: 34rem; margin-top: 2.5rem; font-weight: 700; }
.smp-find input { display: block; width: 100%; min-height: 3.5rem; margin-top: 0.5rem; padding: 0.5rem 1.1rem; border: 2px solid var(--blue); border-radius: 1rem; background: #fff; color: var(--navy); font: 400 1.2rem 'Atkinson Hyperlegible', sans-serif; }

.smp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; align-items: start; margin: 2.5rem 0 0; padding: 0; list-style: none; }
.smp-card { position: relative; border-radius: 1.5rem; background: #fff; box-shadow: 0 3px 0 var(--edge); animation: smp-feed 0.7s cubic-bezier(0.2, 0.7, 0.2, 1) backwards; animation-delay: calc(var(--i) * 120ms + 150ms); }
/* the dashed outline is the die-cut line of the sticker */
.smp-card::after { content: ''; position: absolute; inset: 0.5rem; border: 2px dashed rgba(0, 104, 207, 0.3); border-radius: 1.1rem; pointer-events: none; }
.smp-card.is-open { box-shadow: 0 0 0 3px var(--blue); }
.smp-card__head { all: unset; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; gap: 0.6rem; width: 100%; padding: 1.75rem 1.25rem 1.5rem; border-radius: 1.5rem; text-align: center; cursor: pointer; }
.smp-logo { display: grid; place-items: center; width: 6.5rem; height: 6.5rem; }
.smp-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
.smp-logo__mono { display: grid; place-items: center; width: 100%; height: 100%; border-radius: 1.25rem; background: var(--blue); color: #fff; font: 800 2.2rem 'Archivo Variable', sans-serif; font-stretch: 125%; }
.smp-logo__mono.alt { background: var(--cyan); color: var(--navy); }
.smp-card__name { font: 700 1.4rem/1.2 'Archivo Variable', sans-serif; font-stretch: 110%; }
.smp-card__hint { color: var(--muted); font-size: 1rem; }

.smp-card__panel { display: grid; grid-template-rows: 0fr; visibility: hidden; transition: grid-template-rows 0.3s ease, visibility 0s 0.3s; }
.smp-card.is-open .smp-card__panel { grid-template-rows: 1fr; visibility: visible; transition-delay: 0s; }
.smp-card__clip { min-height: 0; overflow: hidden; }
.smp-opts { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin: 0; padding: 0.75rem 1.1rem 1.5rem; list-style: none; }
.smp-opt { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem; min-height: 6.5rem; padding: 0.6rem; border: 2px solid var(--blue); border-radius: 1rem; background: #eaf4ff; color: var(--blue); text-align: center; }
.smp-opt svg { width: 2.1rem; height: 2.1rem; }
.smp-opt__name { font-weight: 700; font-size: 1.15rem; }
.smp-opt:not(.is-off):hover { background: var(--blue); color: #fff; }
.smp-opt:not(.is-off):active { transform: scale(0.97); }
.smp-opt.is-off { border-style: dashed; border-color: #aebfd1; background: #eef2f6; color: #5b7088; }
.smp-opt small { font-size: 0.85rem; }

.smp-empty { max-width: 32rem; margin-top: 2.5rem; font-size: 1.25rem; }
.smp-link { padding: 0; border: 0; background: none; color: var(--blue); font: inherit; font-weight: 700; text-decoration: underline; cursor: pointer; }

/* Cards print out of the feed slot one after another, top edge first */
@keyframes smp-feed {
    from { clip-path: inset(0 -6px 100% -6px); transform: translateY(-14px); }
    to { clip-path: inset(0 -6px -6px -6px); transform: none; }
}
</style>

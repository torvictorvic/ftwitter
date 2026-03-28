<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
}>()
</script>

<template>
  <nav class="flex flex-wrap items-center gap-2">
    <Link
      v-for="link in links"
      :key="`${link.label}-${link.url}`"
      :href="link.url ?? ''"
      class="rounded-lg border px-3 py-2 text-sm"
      :class="[
        link.active ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-300 bg-white text-slate-700',
        !link.url ? 'pointer-events-none opacity-50' : '',
      ]"
      v-html="link.label"
    />
  </nav>
</template>
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
      class="rounded-lg border px-3 py-2 text-sm transition"
      :class="[
        link.active
          ? 'border-primary bg-primary text-primary-foreground'
          : 'border-border bg-card text-card-foreground hover:bg-accent hover:text-accent-foreground',
        !link.url ? 'pointer-events-none opacity-50' : '',
      ]"
      v-html="link.label"
    />
  </nav>
</template>
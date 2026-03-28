<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import TweetComposer from '@/components/tweets/TweetComposer.vue'
import TweetCard from '@/components/tweets/TweetCard.vue'
import PaginationLinks from '@/components/shared/PaginationLinks.vue'

defineProps<{
  tweets: {
    data: any[]
    links: any[]
  }
}>()
</script>

<template>
  <AppLayout title="Inicio">
    <div class="mx-auto grid max-w-5xl gap-6 px-4 py-6 lg:grid-cols-[minmax(0,1fr)_280px]">
      <section class="space-y-4">
        <TweetComposer />

        <div v-if="tweets.data.length === 0" class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600">
          Tu timeline esta vacio por ahora. Sigue usuarios o crea tu primer tweet.
        </div>

        <TweetCard
          v-for="tweet in tweets.data"
          :key="tweet.id"
          :tweet="tweet"
        />

        <PaginationLinks :links="tweets.links" />
      </section>

      <aside class="space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
          <h2 class="text-sm font-semibold text-slate-900">Sugerencias</h2>
          <p class="mt-2 text-sm text-slate-600">
            Busca usuarios y empieza a seguir cuentas para poblar tu timeline.
          </p>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>
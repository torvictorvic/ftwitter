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

        <div
          v-if="tweets.data.length === 0"
          class="rounded-2xl border border-dashed border-border bg-card p-6 text-sm text-muted-foreground"
        >
         Your timeline is empty for now. Follow users or create your first tweet.
        </div>

        <TweetCard
          v-for="tweet in tweets.data"
          :key="tweet.id"
          :tweet="tweet"
        />

        <PaginationLinks :links="tweets.links" />
      </section>

      <aside class="space-y-4">
        <div class="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
          <h2 class="text-sm font-semibold">Suggestions</h2>
          <p class="mt-2 text-sm text-muted-foreground">
            Search for users and start following accounts to populate your timeline.
          </p>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>
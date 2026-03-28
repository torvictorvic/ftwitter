<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'

interface TweetAuthor {
  name: string
  username: string
  bio: string | null
  initials: string
}

interface Tweet {
  id: number
  body: string
  likes_count: number
  is_liked: boolean
  can_delete: boolean
  created_at_human: string
  author: TweetAuthor
}

const props = defineProps<{
  tweet: Tweet
}>()

const toggleLike = () => {
  if (props.tweet.is_liked) {
    router.delete(route('tweets.likes.destroy', props.tweet.id), {
      preserveScroll: true,
    })
    return
  }

  router.post(route('tweets.likes.store', props.tweet.id), {}, {
    preserveScroll: true,
  })
}

const removeTweet = () => {
  router.delete(route('tweets.destroy', props.tweet.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex items-start gap-3">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-700">
        {{ tweet.author.initials }}
      </div>

      <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
          <Link
            :href="route('users.show', tweet.author.username)"
            class="font-semibold text-slate-900 hover:underline"
          >
            {{ tweet.author.name }}
          </Link>
          <span class="text-sm text-slate-500">@{{ tweet.author.username }}</span>
          <span class="text-sm text-slate-400">· {{ tweet.created_at_human }}</span>
        </div>

        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-800">
          {{ tweet.body }}
        </p>

        <div class="mt-4 flex items-center gap-3">
          <button
            type="button"
            class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-50"
            @click="toggleLike"
            data-test="like-button"
          >
            {{ tweet.is_liked ? 'Quitar like' : 'Like' }} ({{ tweet.likes_count }})
          </button>

          <button
            v-if="tweet.can_delete"
            type="button"
            class="rounded-lg border border-red-300 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50"
            @click="removeTweet"
            data-test="delete-tweet"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </article>
</template>
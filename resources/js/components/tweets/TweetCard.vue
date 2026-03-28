<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import tweets from '@/routes/tweets/index';
import users from '@/routes/users/index';

interface TweetAuthor {
    name: string;
    username: string;
    bio: string | null;
    initials: string;
}

interface Tweet {
    id: number;
    body: string;
    likes_count: number;
    is_liked: boolean;
    can_delete: boolean;
    created_at_human: string;
    author: TweetAuthor;
}

const props = defineProps<{
    tweet: Tweet;
}>();

const toggleLike = (): void => {
    if (props.tweet.is_liked) {
        router.delete(tweets.likes.destroy(props.tweet.id).url, {
            preserveScroll: true,
        });
        return;
    }

    router.post(tweets.likes.store(props.tweet.id).url, {}, {
        preserveScroll: true,
    });
};

const removeTweet = (): void => {
    router.delete(tweets.destroy(props.tweet.id).url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <article class="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-secondary text-sm font-bold text-secondary-foreground">
          {{ tweet.author.initials }}
        </div>
  
        <div class="min-w-0 flex-1">
          <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
            <Link
              :href="users.show(tweet.author.username).url"
              class="font-semibold text-card-foreground hover:underline"
            >
              {{ tweet.author.name }}
            </Link>
            <span class="text-sm text-muted-foreground">@{{ tweet.author.username }}</span>
            <span class="text-sm text-muted-foreground">· {{ tweet.created_at_human }}</span>
          </div>
  
          <p class="mt-3 whitespace-pre-line text-sm leading-6 text-card-foreground">
            {{ tweet.body }}
          </p>
  
          <div class="mt-4 flex items-center gap-3">
            <button
              type="button"
              class="rounded-lg border px-3 py-1.5 text-sm transition"
              :class="tweet.is_liked
                ? 'border-primary bg-primary text-primary-foreground hover:opacity-90'
                : 'border-border bg-secondary text-secondary-foreground hover:bg-accent hover:text-accent-foreground'"
              @click="toggleLike"
              data-test="like-button"
            >
              {{ tweet.is_liked ? 'Remove like' : 'Like' }} ({{ tweet.likes_count }})
            </button>
  
            <button
              v-if="tweet.can_delete"
              type="button"
              class="rounded-lg border border-destructive/40 px-3 py-1.5 text-sm text-destructive transition hover:bg-destructive/10"
              @click="removeTweet"
              data-test="delete-tweet"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </article>
  </template>

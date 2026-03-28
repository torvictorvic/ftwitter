<script setup lang="ts">
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  body: '',
})

const remaining = computed(() => 280 - form.body.length)
const canSubmit = computed(() => form.body.trim().length > 0 && form.body.length <= 280)

const submit = () => {
  form.transform((data) => ({
    ...data,
    body: data.body.trim(),
  })).post('/tweets', {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <form @submit.prevent="submit" class="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
    <label for="tweet-body" class="mb-2 block text-sm font-medium text-card-foreground">
      What's going on?
    </label>

    <textarea
      id="tweet-body"
      v-model="form.body"
      rows="4"
      maxlength="280"
      class="w-full rounded-xl border border-input bg-background px-3 py-2 text-sm text-foreground outline-none placeholder:text-muted-foreground focus:border-ring"
      placeholder="Write your tweet..."
      data-test="tweet-body"
    />

    <div class="mt-3 flex items-center justify-between gap-3">
      <p
        class="text-sm"
        :class="remaining <= 20 ? 'text-amber-600' : 'text-muted-foreground'"
      >
        {{ remaining }} remaining characters
      </p>

      <button
        type="submit"
        class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
        :disabled="form.processing || !canSubmit"
        data-test="tweet-submit"
      >
      Tweet
      </button>
    </div>

    <p v-if="form.errors.body" class="mt-2 text-sm text-destructive">
      {{ form.errors.body }}
    </p>
  </form>
</template>
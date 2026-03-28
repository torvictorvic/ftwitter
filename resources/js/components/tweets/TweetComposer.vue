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
  })).post(route('tweets.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <form @submit.prevent="submit" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <label for="tweet-body" class="mb-2 block text-sm font-medium text-slate-700">
      ¿Que esta pasando?
    </label>

    <textarea
      id="tweet-body"
      v-model="form.body"
      rows="4"
      maxlength="280"
      class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-500"
      placeholder="Escribe tu tweet..."
      data-test="tweet-body"
    />

    <div class="mt-3 flex items-center justify-between">
      <p
        class="text-sm"
        :class="remaining <= 20 ? 'text-amber-600' : 'text-slate-500'"
      >
        {{ remaining }} caracteres restantes
      </p>

      <button
        type="submit"
        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50"
        :disabled="form.processing || !canSubmit"
        data-test="tweet-submit"
      >
        Twittear
      </button>
    </div>

    <p v-if="form.errors.body" class="mt-2 text-sm text-red-600">
      {{ form.errors.body }}
    </p>
  </form>
</template>
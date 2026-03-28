<script setup lang="ts">
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  username: string
  isFollowing: boolean
}>()

const submit = () => {
  if (props.isFollowing) {
    router.delete(route('users.unfollow', props.username), {
      preserveScroll: true,
    })
    return
  }

  router.post(route('users.follow', props.username), {}, {
    preserveScroll: true,
  })
}
</script>

<template>
  <button
    type="button"
    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
    @click="submit"
    data-test="follow-button"
  >
    {{ isFollowing ? 'Siguiendo' : 'Seguir' }}
  </button>
</template>
<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import users from '@/routes/users/index';

const props = defineProps<{
    username: string;
    isFollowing: boolean;
}>();

const submit = (): void => {
    if (props.isFollowing) {
        router.delete(users.unfollow(props.username).url, {
            preserveScroll: true,
        });
        return;
    }

    router.post(users.follow(props.username).url, {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <button
      type="button"
      class="rounded-lg px-4 py-2 text-sm font-semibold transition"
      :class="isFollowing
        ? 'border border-border bg-secondary text-secondary-foreground hover:bg-accent hover:text-accent-foreground'
        : 'bg-primary text-primary-foreground hover:opacity-90'"
      @click="submit"
      data-test="follow-button"
    >
      {{ isFollowing ? 'Siguiendo' : 'Seguir' }}
    </button>
</template>
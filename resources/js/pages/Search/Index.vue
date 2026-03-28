<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue';
import PaginationLinks from '../../components/shared/PaginationLinks.vue';
import FollowButton from '../../components/users/FollowButton.vue';
import usersRoutes from '../../routes/users/index';

const props = defineProps<{
    filters: {
        q: string;
    };
    users: {
        data: any[];
        links: any[];
    };
}>();

const search = ref(props.filters.q);

const submit = (): void => {
    router.get(
        usersRoutes.search().url,
        { q: search.value },
        {
            preserveState: true,
            replace: true,
        },
    );
};
</script>

<template>
    <AppLayout title="Buscar usuarios">
      <div class="mx-auto max-w-4xl space-y-6 px-4 py-6">
        <section class="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
          <form class="flex flex-col gap-3 sm:flex-row" @submit.prevent="submit">
            <input
              v-model="search"
              type="text"
              placeholder="Buscar por nombre o username"
              class="w-full rounded-xl border border-input bg-background px-3 py-2 text-foreground placeholder:text-muted-foreground"
              data-test="search-users"
            />
            <button
              type="submit"
              class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition hover:opacity-90"
            >
              Buscar
            </button>
          </form>
        </section>
  
        <section class="space-y-4">
          <article
            v-for="user in users.data"
            :key="user.id"
            class="flex flex-col gap-4 rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm sm:flex-row sm:items-center sm:justify-between"
          >
            <div>
              <Link
                :href="usersRoutes.show(user.username).url"
                class="font-semibold text-card-foreground hover:underline"
              >
                {{ user.name }}
              </Link>
              <p class="text-sm text-muted-foreground">@{{ user.username }}</p>
              <p v-if="user.bio" class="mt-2 text-sm text-card-foreground">{{ user.bio }}</p>
            </div>
  
            <FollowButton :username="user.username" :is-following="user.is_following" />
          </article>
  
          <PaginationLinks :links="users.links" />
        </section>
      </div>
    </AppLayout>
  </template>
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import FollowButton from '@/components/users/FollowButton.vue'
import PaginationLinks from '@/components/shared/PaginationLinks.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
  filters: {
    q: string
  }
  users: {
    data: any[]
    links: any[]
  }
}>()

const search = ref(props.filters.q)

const submit = () => {
  router.get(route('users.search'), { q: search.value }, {
    preserveState: true,
    replace: true,
  })
}
</script>

<template>
  <AppLayout title="Buscar usuarios">
    <div class="mx-auto max-w-4xl space-y-6 px-4 py-6">
      <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form class="flex flex-col gap-3 sm:flex-row" @submit.prevent="submit">
          <input
            v-model="search"
            type="text"
            placeholder="Buscar por nombre o username"
            class="w-full rounded-xl border border-slate-300 px-3 py-2"
            data-test="search-users"
          />
          <button
            type="submit"
            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
          >
            Buscar
          </button>
        </form>
      </section>

      <section class="space-y-4">
        <article
          v-for="user in users.data"
          :key="user.id"
          class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between"
        >
          <div>
            <Link :href="route('users.show', user.username)" class="font-semibold text-slate-900 hover:underline">
              {{ user.name }}
            </Link>
            <p class="text-sm text-slate-500">@{{ user.username }}</p>
            <p v-if="user.bio" class="mt-2 text-sm text-slate-700">{{ user.bio }}</p>
          </div>

          <FollowButton
            :username="user.username"
            :is-following="user.is_following"
          />
        </article>

        <PaginationLinks :links="users.links" />
      </section>
    </div>
  </AppLayout>
</template>
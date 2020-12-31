<template>
    <div class="card shadow">
        <div class="card-header">{{ $t('pages.profile.profile') }}</div>

        <div class="card-body text-center text-primary">
            <i class="fas fa-user fa-5x"></i>
        </div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                {{ $t('pages.profile.id') }}:
                <span class="text-primary">{{ currentUser.id }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('pages.profile.first_name') }}:
                <span class="text-primary">{{ currentUser.first_name }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('pages.profile.last_name') }}:
                <span class="text-primary">{{ currentUser.last_name }}</span>
            </li>

            <li class="list-group-item">
                {{ $t('pages.profile.email') }}:
                <span class="text-primary">{{ currentUser.email }}</span>
            </li>
        </ul>

        <div class="card-body d-flex w-100 justify-content-end">
            <router-link
                class="btn btn-primary"
                :to="{ name: 'profile.edit' }"
            >
                {{ $t('buttons.edit_profile') }}
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        //
    }
</script>

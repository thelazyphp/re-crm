<template>
    <ul class="navbar-nav ml-auto">
		<li
            v-for="item in items"
            :key="item.id"
            class="nav-item"
        >
            <router-link
                :to="item.path"
                class="nav-link"
                :exact="item.exact || false"
                active-class="active"
                exact-active-class="active"
            >
                {{ item.text }}
            </router-link>
		</li>
	</ul>
</template>

<script>
export default {
    name: 'TheHeaderMenu',

    computed: {
        items () {
            return [
                { id: 1, path: '/', text: 'Главная', exact: true },
                { id: 2, path: '/posts', text: 'Посты' },
            ];
        }
    }
};
</script>

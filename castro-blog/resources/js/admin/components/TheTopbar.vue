<template>
	<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
		<button
			id="sidebarToggleTop"
			class="btn btn-link d-md-none rounded-circle mr-3"
		>
			<i class="fa fa-bars"></i>
		</button>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown no-arrow">
				<a
					href="javascript:void(0);"
					class="nav-link dropdown-toggle"
					role="button"
					data-toggle="dropdown"
					aria-haspopup="true"
					aria-expanded="false"
				>
					<span class="mr-2 d-none d-lg-inline text-gray-600 small">
						{{ user.name }}
					</span>

					<img
						src="/castro-blog/theme/admin/img/undraw_profile.svg"
						class="img-profile rounded-circle"
					/>
				</a>

				<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
					<a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Настройки</a>
					<div class="dropdown-divider"></div>
                    <a href="javascript:void(0);" class="dropdown-item" @click.prevent="logout"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Выйти</a>
				</div>
			</li>
		</ul>
	</nav>
</template>

<script>
import axios from 'axios';

import {
    SET_IS_AUTH,
} from '../store/mutation-types';

export default {
    name: 'TheTopbar',

    computed: {
        user () {
            return this.$store.state.user;
        }
    },

    methods: {
        async logout () {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/logout');
                this.$store.commit(SET_IS_AUTH, false);
                this.$router.push('/login');
            } catch (error) {
                console.log(error);
            }
        }
    }
};
</script>

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
					href="#"
					class="nav-link dropdown-toggle"
					role="button"
					data-toggle="dropdown"
					aria-haspopup="true"
					aria-expanded="false"
				>
					<span class="mr-2 d-none d-lg-inline text-gray-600 small">
						{{ userFullName }}
					</span>

					<img
						src="/nca/theme/img/undraw_profile.svg"
						alt="..."
						class="img-profile rounded-circle"
					/>
				</a>

				<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
					<a href="#" class="dropdown-item">
						<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Профиль
					</a>
					<a href="#" class="dropdown-item">
						<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Настройки
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item" @click.prevent="logout">
						<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Выйти
					</a>
				</div>
			</li>
		</ul>
	</nav>
</template>

<script>
import axios from 'axios';

export default {
    name: 'TheNavbar',

    computed: {
        user () {
            return this.$store.state.user;
        },

        userFullName () {
            return [this.user.first_name, this.user.last_name].join(' ');
        }
    },

    methods: {
        async logout () {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('logout');
                this.$store.commit('SET_USER', null);
                this.$store.commit('SET_IS_AUTH', false);

                this.$router.push({
                    name: 'login'
                });
            } catch (error) {
                //

                console.log(error);
            }
        }
    }
};
</script>

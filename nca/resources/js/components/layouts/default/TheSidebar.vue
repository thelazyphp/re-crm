<template>
	<ul
		id="accordionSidebar"
		class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
	>
		<router-link
			to="/"
			class="sidebar-brand d-flex align-items-center justify-content-center"
		>
			<div class="sidebar-brand-icon rotate-n-15">
				<i class="fas fa-laugh-wink"></i>
			</div>
			<div class="sidebar-brand-text mx-3">
				База БТИ
			</div>
		</router-link>

		<hr class="sidebar-divider my-0"/>

		<router-link
			to="/dashboard"
			:exact="false"
			v-slot="{ href, navigate, isActive, isExactActive }"
		>
			<li
				class="nav-item"
				:class="{ active: isActive || isExactActive }"
			>
				<a
					:href="href"
					class="nav-link"
					@click="navigate"
				>
					<i class="fas fa-fw fa-tachometer-alt"></i>
					<span>
						Дашборд
					</span>
				</a>
			</li>
		</router-link>

		<hr class="sidebar-divider"/>

		<router-link
			v-for="link in links"
			:key="link.id"
			:to="link.path"
			:exact="link.exact || false"
			v-slot="{ href, navigate, isActive, isExactActive }"
		>
			<li
				class="nav-item"
				:class="{ active: isActive || isExactActive }"
			>
				<a
					:href="href"
					class="nav-link"
					@click="navigate"
				>
					<i
						v-if="link.icon"
						:class="link.icon"
					>
					</i>

					<span>
						{{ link.text }}
					</span>
				</a>
			</li>
		</router-link>

		<hr class="sidebar-divider d-none d-md-block"/>

		<div class="text-center d-none d-md-inline">
			<button
				id="sidebarToggle"
				class="rounded-circle border-0"
			>
			</button>
		</div>
	</ul>
</template>

<script>
export default {
	name: 'TheSidebar',

	computed: {
		links () {
			return [
				{ id: 1, path: '/props', text: 'Записи', icon: 'fas fa-fw fa-folder' },
				{ id: 2, path: '/import', text: 'Импорт', icon: 'fas fa-fw fa-folder' },
			];
		}
	}
};
</script>

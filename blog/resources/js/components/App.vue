<template>
	<div id="app">
		<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
			<div class="container-fluid">
				<router-link class="navbar-brand"
							 :to="{
                                 name: 'dashboard'
                             }">
					{{ name }}
				</router-link>
				<button class="navbar-toggler"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#navbarSupportedContent"
						aria-controls="navbarSupportedContent"
						aria-expanded="false"
						aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div id="navbarSupportedContent"
					 class="collapse navbar-collapse">
                    <!-- mb-2 mb-lg-0 -->
					<ul class="navbar-nav me-auto">
						<li v-if="resources.length"
                            class="nav-item dropdown">
							<a id="navbarDropdown"
							   class="nav-link dropdown-toggle"
							   href="#"
							   role="button"
							   data-bs-toggle="dropdown"
							   aria-expanded="false">
								Resources
							</a>
							<ul class="dropdown-menu"
								aria-labelledby="navbarDropdown">
								<li v-for="(resource, index) in resources"
									:key="index">
									<router-link class="dropdown-item"
                                                 :to="{
                                                     name: 'index',
                                                     params: {
                                                         resourceKey: resource.key
                                                     }
                                                 }"
                                                 :aria-current="$route.name === 'index' && $route.params.resourceKey === resource.key ? 'page' : null">
                                        {{ resource.pluralName }}
                                    </router-link>
                                </li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
        <div class="py-4">
            <router-view/>
        </div>
	</div>
</template>

<script>
export default {
	computed: {
		name () {
			return window.config.name;
		},

		resources () {
			return window.config.resources.filter(resource => resource.displayInNavigation);
		}
	}
};
</script>

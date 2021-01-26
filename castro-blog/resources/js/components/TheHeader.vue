<template>
    <header class="header">
		<nav class="navbar navbar-expand-lg">
			<div class="search-area">
				<div class="search-area-inner d-flex align-items-center justify-content-center">
					<div class="close-btn">
                        <i class="icon-close"></i>
                    </div>
					<div class="row d-flex justify-content-center">
						<div class="col-md-8">
							<form @submit.prevent>
								<div class="form-group">
									<input
                                        id="search"
                                        type="text"
                                        name="search"
                                        placeholder="Что вы хотите найти?"
                                    />

									<button
                                        type="submit"
                                        class="submit"
                                    >
                                        <i class="icon-search-1"></i>
                                    </button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="navbar-header d-flex align-items-center justify-content-between">
					<router-link
                        to="/"
                        class="navbar-brand"
                    >
                        Castro Blog
                    </router-link>

					<button
                        type="button"
                        class="navbar-toggler"
                        data-toggle="collapse"
                        data-target="#navbarcollapse"
                        aria-controls="navbarcollapse"
                        aria-expanded="false"
                        aria-label="Показать/скрыть навигацию"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
				</div>
				<div
                    id="navbarcollapse"
                    class="collapse navbar-collapse"
                >
					<TheHeaderMenu/>
					<div class="navbar-text">
                        <a
                            href="javascript:void(0);"
                            class="search-btn"
                        >
                            <i class="icon-search-1"></i>
                        </a>
                    </div>
				</div>
			</div>
		</nav>
	</header>
</template>

<script>
import TheHeaderMenu from './TheHeaderMenu.vue';

export default {
    name: 'TheHeader',

    components: {
        TheHeaderMenu,
    }
};
</script>

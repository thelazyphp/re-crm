<template>
	<div class="container">
		<div class="row">
			<main class="posts-listing col-lg-8">
				<div class="container">
					<div class="row">
						<div
							v-for="post in posts"
							:key="post.id"
							class="post col-xl-6"
						>
							<div
								v-if="post.image"
								class="post-thumbnail"
							>
								<a href="javascript:void(0);">
									<img
										:src="post.image.url"
										alt="..."
										class="img-fluid"
									/>
								</a>
							</div>
							<div class="post-details">
								<div class="post-meta d-flex justify-content-between">
									<div class="date meta-last">
										{{ new Date(post.created_at).toLocaleDateString() }}
									</div>

									<div
										v-if="post.category"
										class="category"
									>
										<a href="javascript:void(0);">
											{{ post.category.name }}
										</a>
									</div>
								</div>
								<a href="javascript:void(0);">
									<h3 class="h4">
										{{ post.title }}
									</h3>
								</a>

								<p
									class="text-muted"
									v-html="post.body"
								>
								</p>

								<footer class="post-footer d-flex align-items-center">
									<a
										href="javascript:void(0);"
										class="author d-flex align-items-center flex-wrap"
									>
										<div class="avatar">
											<img
												src="/castro-blog/theme/img/avatar-3.jpg"
												alt="..."
												class="img-fluid"
											/>
										</div>
										<div class="title">
											<span>
												{{ post.user.name }}
											</span>
										</div>
									</a>
									<div class="date">
										<i class="icon-clock"></i>
										2 дня назад
									</div>
									<div class="comments meta-last">
										<i class="icon-comment"></i>
										12
									</div>
								</footer>
							</div>
						</div>
					</div>
				</div>
			</main>
			<aside class="col-lg-4">
                <div class="widget search">
                    <header>
                        <h3 class="h6">
                            Поиск
                        </h3>
                    </header>

                    <form
                        class="search-form"
                        @submit.prevent
                    >
                        <div class="form-group">
                            <input
                                type="text"
                                placeholder="Что вы хотите найти?"
                            />

                            <button
                                type="submit"
                                class="submit"
                            >
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
				<div class="widget categories">
					<header>
					    <h3 class="h6">
						    Категории
						</h3>
					</header>

					<div
						v-for="category in categories"
						:key="category.id"
						class="item d-flex justify-content-between"
					>
						<a href="javascript:void(0);">
							{{ category.name }}
						</a>
						<span>
							{{ category.posts_count }}
						</span>
					</div>
				</div>
			</aside>
		</div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'PostsPage',

	data () {
		return {
			posts: [],
			categories: []
		}
	},

	async created () {
		await this.fetchPosts();
		await this.fetchCategories();
	},

	methods: {
		async fetchPosts () {
			try {
				const { data } = await axios.get('/api/posts');
				this.posts = data.data;
			} catch (error) {
				//

				console.log(error);
			}
		},

		async fetchCategories () {
			try {
				const { data } = await axios.get('/api/categories');
				this.categories = data.data.filter(category => category.posts_count > 0);
			} catch (error) {
				//

				console.log(error);
			}
		}
	}
};
</script>

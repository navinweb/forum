<template>
    <button :class="classes" @click="toogle">
        <i class="fa fa-heart"></i>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
	export default {
		props: ['reply'],

		data() {
			return {
				favoritesCount: this.reply.favoritesCount,
				isFavorited: this.reply.isFavorite
			}
		},

		computed: {
			classes() {
				return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
			},

			endpoint() {
				return '/replies/' + this.reply.id + '/favorites';
			}
		},

		methods: {
			toogle(){
				return this.isFavorited ? this.destroy() : this.create();
			},

			create() {
				axios.post(this.endpoint);

				this.isFavorited = true;
				this.favoritesCount++;
			},

			destroy() {
				axios.delete(this.endpoint);

				this.isFavorited = false;
				this.favoritesCount--;
			}
		}
	}
</script>
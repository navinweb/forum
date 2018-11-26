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
				isFavorited: false
			}
		},

		computed: {
			classes() {
				return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
			}
		},

		methods: {
			toogle(){
				if (this.isFavorited) {
					axios.delete('/replies/' + this.reply.id + '/favorites');
				} else {
					axios.post('/replies/' + this.reply.id + '/favorites');

					this.isFavorited = true;
					this.favoritesCount++;
				}
			}
		}
	}
</script>
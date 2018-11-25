<template>
    <button class="btn btn-default" @click="toogle">
        <i class="fa fa-heart"></i>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
	export default {
		props: ['reply'],

		data() {
			return {
				favoritesCount: 10
			}
		},

		methods: {
			toogle(){
				if (this.isFavorited) {
					axios.delete('/replies/' + this.reply.id + '/favorites');
				} else {
					axios.post('/replies/' + this.reply.id + '/favorites');
				}
			}
		}
	}
</script>
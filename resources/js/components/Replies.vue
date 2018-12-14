<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
	import Reply from './Reply.vue';
	import NewReply from './NewReply.vue';

	export default {
		components: {Reply, NewReply},

		data() {
			return {
				items: [],
                endpoint: location.pathname + '/replies'
			}
		},

        created() {
            this.fetch();
        },

		methods: {
			fetch() {
                axios.get(this.url)
                    .then(this.refresh);
            },

			refresh(response){

            },

			add(reply){
                this.items.push(reply);

				this.$emit('added');
            },

			remove(index) {
				this.items.splice(index, 1);

				this.$emit('removed');

				flash('Reply was deleted!');
			}
		}
	}
</script>
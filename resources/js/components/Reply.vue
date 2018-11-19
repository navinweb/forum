<script>
    export default {
    	props: ['attributes'],
        data(){
        	return {
		        editing: false,
                body: this.attributes.body
            }
        },
	    methods: {
		    update() {
		    	console.log('update');
			    axios.patch('/replies/' + this.attributes.id, {
				    body: this.body
			    });
			    this.editing = false;
			    flash('Updated!');
		    },
		    test: function (event) {
		    	console.log('123');
			    // `this` внутри методов указывает на экземпляр Vue
			    alert('Привет');
			    // `event` — нативное событие DOM
			    if (event) {
				    alert(event.target.tagName)
			    }
		    }
	    }
    }
</script>
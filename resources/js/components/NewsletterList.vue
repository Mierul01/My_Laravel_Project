<template>
    <div>
        <ul class="newsletter-list">
            <li v-for="newsletter in newsletters" :key="newsletter.id" class="newsletter-item">
                <div class="newsletter-content">
                    <div class="newsletter-image">
                        <img :src="getImageUrl(newsletter.image)" alt="Newsletter Image">
                    </div>
                    <div>
                        <p class="newsletter-title">{{ newsletter.title }}</p>
                        <p class="newsletter-text">{{ newsletter.content }}</p>
                    </div>
                </div>
                <div class="newsletter-actions">
                    <button @click="deleteNewsletter(newsletter.id)" class="btn btn-delete">Delete</button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newsletters: [],
        };
    },
    mounted() {
        this.loadNewsletters();

        window.Echo.channel('newsletters')
            .listen('NewsletterUpdated', (e) => {
                this.loadNewsletters();
            });
    },
    methods: {
        loadNewsletters() {
            axios.get('/api/newsletters')
                .then(response => {
                    this.newsletters = response.data;
                });
        },
        getImageUrl(image) {
            return image ? `/images/${image}` : 'https://via.placeholder.com/100';
        },
        deleteNewsletter(id) {
            axios.delete(`/api/newsletters/${id}`)
                .then(response => {
                    this.loadNewsletters();
                });
        }
    }
};
</script>

<style scoped>
/* Add your styles here */
</style>

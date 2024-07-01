import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import NewsletterList from './components/NewsletterList.vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Vue.js
const app = createApp({});
app.component('newsletter-list', NewsletterList);
app.mount('#app');

// Initialize Laravel Echo with Pusher
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.axios = require('axios');

// Function to update the newsletter item in the DOM
function updateNewsletterItem(newsletter) {
    const newsletterItem = document.querySelector(`.newsletter-item[data-id="${newsletter.id}"]`);
    if (newsletterItem) {
        newsletterItem.querySelector('.newsletter-title a').innerText = newsletter.title;
        newsletterItem.querySelector('.newsletter-content p').innerText = newsletter.content.substring(0, 100);
        newsletterItem.querySelector('.newsletter-image img').src = `/images/${newsletter.image}`;
    }
}

// Listen for the NewsletterUpdated event
Echo.channel('newsletters')
    .listen('NewsletterUpdated', (e) => {
        console.log('Newsletter updated:', e);
        updateNewsletterItem(e.newsletter);
    });

// Listen for the NewsletterCreated event
Echo.channel('newsletters')
    .listen('NewsletterCreated', (e) => {
        let newslettersList = document.querySelector('.newsletter-list');
        let newNewsletter = `
            <li class="newsletter-item" data-id="${e.newsletter.id}">
                <div class="newsletter-content">
                    <div class="newsletter-image">
                        <img src="/images/${e.newsletter.image}" alt="Newsletter Image">
                    </div>
                    <div>
                        <p class="newsletter-title">
                            <a href="/newsletters/${e.newsletter.id}">${e.newsletter.title}</a>
                        </p>
                        <p>${e.newsletter.content.substring(0, 100)}</p>
                    </div>
                </div>
                <div class="newsletter-actions">
                    <a href="/newsletters/${e.newsletter.id}" class="btn btn-read-more">Read More</a>
                </div>
            </li>
        `;
        newslettersList.insertAdjacentHTML('afterbegin', newNewsletter);
    });

<script>
import { isUrl, matchProvider } from '../helpers/validators.js'

export default {
    extends: 'k-text-input',
    props: {
        provider: String,
        media: Object,
    },
    mounted() {
        this.loadEmbedScripts()
    },
    methods: {
        onInput(value) {
            if(!this.isValidUrl(value)) {
                this.media = {}
                this.emitInput(value)
                return false;
            }

            this.$emit('startLoading')
            this.$api
                .get('kirby-discogs/get-data', { url: value })
                .then(response => {
                    if(response['status'] == 'success' && response['data']) {
                        this.media = response['data']
                    }
                    else {
                        this.media = {}
                    }

                    this.emitInput(value)
                })
                .catch(error => {
                    this.media = {}
                    this.emitInput(value)
                })
        },
        emitInput(value) {
            this.$emit("input", { input: value, media: this.media });
            this.$emit("setMedia", this.media)

            this.loadEmbedScripts()
        },
        loadEmbedScripts() {
            if (window.twttr) {
                window.twttr.widgets.load();
            }
            else if (this.media && Object.keys(this.media).length && this.media.providerName.toLowerCase() == 'twitter') {
                const discogs = document.createElement('script');
                      discogs.src = 'https://platform.twitter.com/widgets.js';
                      document.body.appendChild(discogs);
            }

            if (window.instgrm) {
                window.instgrm.Embeds.process();
            }
            else if (this.media && Object.keys(this.media).length && this.media.providerName.toLowerCase() == 'instagram') {
                const discogs = document.createElement('script');
                      discogs.src = 'https://www.instagram.com/discogs.js';
                      document.body.appendChild(discogs);
            }
        },
        isValidUrl(value) {
            if(!isUrl(value)) return false
            if(this.provider && !matchProvider(value, this.provider)) return false
            return true
        }
    },
};
</script>

import discogsField from './components/discogsField.vue'
import discogsInput from './components/discogsInput.vue'

panel.plugin('tristantbg/discogs', {
    fields: {
        discogs: discogsField,
    },
    components: {
        'k-discogs-input': discogsInput,
    }
});

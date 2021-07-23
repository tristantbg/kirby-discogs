<template>
  <div>
    <!-- <k-input ref="input" :id="_uid" v-bind="$props" :value="inputValue" :media="media" theme="field" v-on="$listeners" @setMedia="setMedia" @startLoading="startLoading">
        <div class="k-discogs-infos" slot="icon">
            <div class="k-discogs-status">
                <span v-if="loading" class="k-discogs-status-loading"><span class="loader"></span></span>
                <span v-else-if="hasMedia" class="k-discogs-status-synced">{{ $t('discogs.synced') }} <span class="checkmark"></span></span>
                <span v-else-if="syncFailed" class="k-discogs-status-failed">{{ $t('discogs.failed') }} <span class="cross"></span></span>
            </div>
            <k-button v-if="link"
                      :icon="icon"
                      :link="inputValue"
                      :tooltip="$t('open')"
                      class="k-input-icon-button"
                      tabindex="-1"
                      target="_blank"
                      rel="noopener" />
        </div>

    </k-input> -->
    <div class="k-field" data-type="discogs">
      <k-autocomplete ref="autocomplete" :options="options" @select="select">
        <input type="text" placeholder="Search release…" @input="onInput"/>
        <!-- <div data-type="discogs" class="k-input">
          <span class="k-input-element">

          </span>
        </div> -->
      </k-autocomplete>
    </div>
    <div class="preview" v-if="hasMedia">
        <div class="preview-content">
          <img :src="media.cover_image" width="100%">
        </div>
        <div class="media-title">
          <div>{{ media.title }}</div>
          <div>{{ media.year }}</div>
        </div>
    </div>
  </div>
</template>

<script>
import { isUrl, matchProvider } from '../helpers/validators.js'

export default {
    extends: 'k-text-field',
    data() {
        return {
            options: [],
            media: Object,
            loading: false,
        }
    },
    props: {
        provider: String,
    },
    created() {
        if(this.value && this.value.media && this.hasLength(this.value.media)) {
            this.media = this.value.media
        }
    },
    computed: {
        hasMedia() {
            return this.hasLength(this.media)
        },
        syncFailed() {
            return this.inputValue != '' && !this.hasMedia
        },
        inputValue() {
            return this.value && this.value.input ? this.value.input : ''
        }
    },
    methods: {
        onInput(e) {
            const value = e.target.value
            if(!this.isValidInput(value)) {
                // this.media = {}
                this.emitInput(value)
                return false;
            }

            this.$emit('startLoading')
            this.$api
                .get('kirby-discogs/get-data', { search: value })
                .then(response => {
                    if(response['status'] == 'success' && response['data']) {
                        let results = JSON.parse(response['data'])
                        results = results.results.map(el => ({...el, text: `${el.title} (${el.year})`}))
                        this.options = results
                        console.log(this.options)
                        this.$refs.autocomplete.search(value)
                    }
                    else {
                        // this.media = {}
                    }


                })
                .catch(error => {
                    // this.media = {}
                    this.emitInput(value)
                })
        },
        emitInput(value) {
            this.$emit("input", { input: value, media: this.media });
            this.$emit("setMedia", this.media)
        },
        select(item) {
          console.log(item);
          this.media = item
          this.emitInput(item.text)
        },
        setMedia(media) {
            this.media = media
            this.stopLoading()
        },
        hasLength(obj) {
            return Object.keys(obj).length
        },
        startLoading() {
            this.loading = true
        },
        stopLoading() {
            this.loading = false
        },
        isValidInput(value) {
            return true
        }
    }
};
</script>

<style lang="scss">
    @import '../assets/css/styles.scss'
</style>

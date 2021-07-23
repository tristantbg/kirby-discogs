<template>
  <div>
    <div class="k-field" data-type="discogs">
      <div class="k-discogs-infos">
        <k-autocomplete ref="autocomplete" :options="options" @select="select">
          <input type="text" :placeholder="$t('discogs.placeholder')" @input="onInput" autocomplete="off"/>
        </k-autocomplete>
        <div class="k-discogs-status">
          <span v-if="loading" class="k-discogs-status-loading"><span class="loader"></span></span>
          <span v-else-if="hasMedia" class="k-discogs-status-clear" @click="clearMedia">{{ $t('discogs.clear') }} <span class="cross"></span></span>
        </div>
      </div>
    </div>
    <div class="preview" v-if="hasMedia">
        <div class="preview-content">
          <div class="preview-content">
            <img :src="media.cover_image" width="100%">
          </div>
          <div class="preview-background"></div>
          <div class="preview-title">
            <div>{{ media.title }}</div>
            <div>{{ media.year }}</div>
          </div>
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
            timeoutId: null,
            debounceDelay: 300,
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
            clearTimeout(this.timeoutId)
            this.timeoutId = setTimeout(() => {
              if(!this.isValidInput(value)) {
                  // this.media = {}
                  this.emitInput(value)
                  return false;
              }

              this.startLoading()
              this.$api
                  .get('kirby-discogs/get-data', { search: value })
                  .then(response => {
                      if(response['status'] == 'success' && response['data']) {
                          let results = JSON.parse(response['data'])
                          results = results.results.map(el => ({...el, text: `${el.title} (${el.year})`}))
                          this.options = results
                          setTimeout(function() {
                            this.$refs.autocomplete.search(value)
                            this.stopLoading()
                          }.bind(this), 100);
                      }
                      else {
                          // this.media = {}
                      }


                  })
                  .catch(error => {
                      this.media = {}
                      this.emitInput(value)
                  })
            }, this.debounceDelay);
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
        clearMedia() {
          this.media = {}
          this.emitInput('')
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

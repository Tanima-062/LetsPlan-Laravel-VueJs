<template>
    <div style="min-width: 500px" v-on-clickaway="closeSearch" class="relative z-50 w-full max-w-xs">
        <div class="relative">
            <!-- Search -->
            <div class="relative">
                <img src="/images/search.png" class="absolute search-icon-center ml-3 text-80"/>
                <input
                    dusk="global-search"
                    ref="input"
                    @input.stop="search"
                    @keydown.stop=""
                    @keydown.enter.stop="goToCurrentlySelectedResource"
                    @keydown.esc.stop="closeSearch"
                    @focus="openSearch"
                    @keydown.down.prevent="move(1)"
                    @keydown.up.prevent="move(-1)"
                    v-model="searchTerm"
                    type="search"
                    class="pl-search w-full form-global-search"
                    spellcheck="false"
                />
            </div>

            <!-- Loader -->
            <div
                v-if="loading"
                class="
          bg-white
          py-3
          overflow-hidden
          absolute
          rounded-lg
          shadow-lg
          w-full
          mt-2
          max-h-search
          overflow-y-auto
        "
            >

            </div>

            <!-- No Results Found -->
            <div
                v-if="shouldShowNoResults"
                class="
          bg-white
          overflow-hidden
          absolute
          rounded-lg
          shadow-lg
          w-full
          mt-2
          max-h-search
          overflow-y-auto
        "
            >
                <h3 class="text-xs uppercase tracking-wide text-80 bg-40 py-4 px-3">
                    No Results Found
                </h3>
            </div>

            <!-- Results -->
            <div
                v-if="shouldShowResults"
                class="
          overflow-hidden
          absolute
          rounded-lg
          shadow-lg
          w-full
          mt-2
          max-h-search
          overflow-y-auto
        "
                ref="container"
            >
                <div v-for="group in formattedResults">
                    <h3 class="text-xs uppercase tracking-wide text-80 bg-40 py-2 px-3">
                        {{ group.resourceTitle }}
                    </h3>

                    <ul class="list-reset">
                        <li
                            v-for="item in group.items"
                            :key="item.resourceName + ' ' + item.index"
                            :ref="item.index === highlightedResultIndex ? 'selected' : null"
                        >
                            <a
                                :dusk="item.resourceName + ' ' + item.index"
                                @click.prevent="navigate(item.index)"
                                class="
                  cursor-pointer
                  flex
                  items-center
                  hover:bg-20
                  block
                  py-2
                  px-3
                  no-underline
                  font-normal
                "
                                :class="{
                  'bg-white': highlightedResultIndex != item.index,
                  'bg-20': highlightedResultIndex == item.index,
                }"
                            >
                                <img
                                    v-if="item.avatar"
                                    :src="item.avatar"
                                    class="h-8 w-8 mr-3"
                                    :class="{
                    'rounded-full': item.rounded,
                    rounded: !item.rounded,
                  }"
                                />

                                <div>
                                    <p class="text-90">{{ item.title }}</p>
                                    <p v-if="item.subTitle" class="text-xs mt-1 text-80">
                                        {{ item.subTitle }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mixin as clickaway} from 'vue-clickaway'
import {debounce} from "lodash";
export default {
    mixins: [clickaway],
    data: () => ({
        canceller: null,
        loading: false,
        currentlySearching: false,
        searchTerm: '',
        results: [],
        highlightedResultIndex: 0,
    }),

    watch: {
        $route: function () {
            this.closeSearch()
        }
    },

    mounted() {
        this.openSearch()
        return false
    },

    methods: {
        isNotInputElement(event) {
            const tagName = event.target.tagName
            return Boolean(tagName !== 'INPUT' && tagName !== 'TEXTAREA')
        },

        openSearch() {
            this.clearSearch()
            this.$refs.input.focus()
            this.currentlySearching = true
            this.clearResults()
        },

        closeSearch() {
            this.clearSearch()
            this.clearResults()
            this.$refs.input.blur()
            this.currentlySearching = false
            this.loading = false
        },

        clearSearch() {
            this.searchTerm = ''
        },

        clearResults() {
            this.results = []
        },

        search(event) {
            this.highlightedResultIndex = 0
            this.loading = true

            if (this.searchTerm == '') {
                if (this.canceller !== null) this.canceller()

                this.loading = false
                this.results = []
            } else {
                debounce(() => {
                    if (this.canceller !== null) this.canceller()

                    this.fetchResults(event.target.value)
                }, 500)
            }
        },

        async fetchResults(search) {
            this.results = []
            console.log(search)
        },

        move(offset) {
            if (this.results.length) {
                let newIndex = this.highlightedResultIndex + offset

                if (newIndex < 0) {
                    this.highlightedResultIndex = this.results.length - 1
                    this.updateScrollPosition()
                } else if (newIndex > this.results.length - 1) {
                    this.highlightedResultIndex = 0
                    this.updateScrollPosition()
                } else if (newIndex >= 0 && newIndex < this.results.length) {
                    this.highlightedResultIndex = newIndex
                    this.updateScrollPosition()
                }
            }
        },

        updateScrollPosition() {
            const selection = this.$refs.selected
            const container = this.$refs.container

            this.$nextTick(() => {
                if (selection) {
                    if (
                        selection[0].offsetTop >
                        container.scrollTop +
                        container.clientHeight -
                        selection[0].clientHeight
                    ) {
                        container.scrollTop =
                            selection[0].offsetTop +
                            selection[0].clientHeight -
                            container.clientHeight
                    }
                    if (selection[0].offsetTop < container.scrollTop) {
                        container.scrollTop = selection[0].offsetTop
                    }
                }
            })
        },

        navigate(index) {
            this.highlightedResultIndex = index
            this.goToCurrentlySelectedResource()
        },

        goToCurrentlySelectedResource() {
            const resource = _.find(
                this.indexedResults,
                res => res.index == this.highlightedResultIndex
            )

            this.$router.push({
                name: resource.linksTo,
                params: {
                    resourceName: resource.resourceName,
                    resourceId: resource.resourceId,
                },
            })

            this.closeSearch()
        },
    },

    computed: {
        hasResults() {
            return this.results.length > 0
        },

        hasSearchTerm() {
            return this.searchTerm !== ''
        },

        shouldShowNoResults() {
            return (
                this.currentlySearching &&
                !this.loading &&
                !this.hasResults &&
                this.hasSearchTerm
            )
        },

        shouldShowResults() {
            return this.currentlySearching && this.hasResults && !this.loading
        },

        indexedResults() {
            return _.map(this.results, (item, index) => {
                return {index, ...item}
            })
        },

        formattedGroups() {
            return _.chain(this.indexedResults)
                .map(item => {
                    return {
                        resourceName: item.resourceName,
                        resourceTitle: item.resourceTitle,
                    }
                })
                .uniqBy('resourceName')
                .value()
        },

        formattedResults() {
            return _.map(this.formattedGroups, group => {
                return {
                    resourceName: group.resourceName,
                    resourceTitle: group.resourceTitle,
                    items: _.filter(
                        this.indexedResults,
                        item => item.resourceName == group.resourceName
                    ),
                }
            })
        },
    },
}
</script>

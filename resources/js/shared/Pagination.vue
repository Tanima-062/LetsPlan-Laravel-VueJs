<template>
    <div class="pagination-links max-w-8xl mx-auto container py-10" v-if="collection.meta && collection.meta.total > 0">
        <ul class="flex justify-center items-center list-none">
            <li>
                 <button
                     :class="{'disabled': collection.meta.current_page == 1}"
                     @click.prevent="paginationClickHandler(1)"
                     class=" prev-link-first flex rounded transition duration-150 ease-in-out text-base leading-tight font-bold text-gray-500 hover:text-indigo-700 focus:outline-none mr-1 sm:mr-3">
                     <icon name="angles-left"/>
                </button>
            </li>
            <li>
               <button
                    @click.prevent="paginationClickHandler(collection.meta.current_page - 1)"
                    :class="{'disabled': collection.meta.current_page <= 1}"
                     class=" flex prev-link rounded transition duration-150 ease-in-out text-base leading-tight font-bold text-gray-500 hover:text-indigo-700 focus:outline-none sm:mr-3">
                      <icon name="angle-left"/>
               </button>
            </li>
            <li v-for="page in collection.meta.links.slice(1, -1)" :key="page.label">
                   <button
                     :class="{'disabled':collection.meta.current_page == page.label}"
                      @click.prevent="paginationClickHandler(page.label)"
                      class="flex text-indigo-700 hover:bg-indigo-600 hover:text-white text-base leading-tight font-bold cursor-pointer transition duration-150 ease-in-out ml-1 sm:mx-4 rounded  focus:outline-none">
                       {{ page.label }}
                   </button>
            </li>
            <li>
                <button
                    :class="{'disabled': collection.meta.current_page >= collection.meta.last_page}"
                    @click.prevent="paginationClickHandler(collection.meta.current_page + 1)"
                    class="flex next-link rounded transition duration-150 ease-in-out text-base leading-tight font-bold text-gray-500 hover:text-indigo-700  focus:outline-none ml-1 sm:ml-3">
                    <icon name="angle-right"/>
                </button>
            </li>
            <li>
                <button
                    :class="{'disabled': collection.meta.current_page == collection.meta.last_page}"
                    @click.prevent="paginationClickHandler(collection.meta.last_page)"
                    class="flex next-link-last rounded transition duration-150 ease-in-out text-base leading-tight font-bold text-gray-500 hover:text-indigo-700  focus:outline-none ml-1 sm:ml-3">
                    <icon name="angles-right"/>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
import Icon from "./Icon";

export default {
    name: "Pagination",
    components: {
        Icon
    },
    props: {
        collection: {
            type: [Array, Object],
            required: true
        }
    },
    methods: {
        paginationClickHandler(page) {
            this.$emit('loadResource', page)
        }
    }
}
</script>

<style scoped lang="scss">
.pagination-links {
    margin: 20px 0px;
    ul{
        list-style: none;
        li {
            button{
                font-weight: 400;
                font-size: 12px;
                width: 33px;
                height: 33px;
                background: transparent;
                color: #727574;
                text-align: center;
                line-height: 32px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 2px;
                &.disabled,
                &.prev-link-first,
                &.next-link-last{
                    color: white;
                    background: #72757466;
                }
                &.disabled{
                    pointer-events: none;
                }
                &.prev-link,
                &.next-link{
                    color: white;
                    background: #AEC90B;
                }
            }
        }
    }
}
</style>

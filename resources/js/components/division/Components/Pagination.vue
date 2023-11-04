<template>
    <div class="pagination__content">
      <ul class="pagination__body" v-if="show">
        <!-- <li :class="{ disabled: !prev }" class="pagination-item">
          <a @click.prevent="goToPage(1)" class="pagination-link" href="#">
            <svg width="14" height="22" viewBox="0 0 14 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.333 2.66699L2.99967 11.0003L11.333 19.3337" stroke="#063F5F" stroke-width="3" stroke-linecap="square"/>
            </svg>
          </a>
        </li> -->
        <li :class="{ disabled: !prev }" class="pagination-item">
          <a @click.prevent="goToPage(pagination.current_page - 1)" class="pagination-link" href="#">
            <svg width="14" height="22" viewBox="0 0 14 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.333 2.66699L2.99967 11.0003L11.333 19.3337" stroke="#063F5F" stroke-width="3" stroke-linecap="square"/>
            </svg>
          </a>
        </li>

        <li
          v-for="(link, index) in links"
          :key="index"
          :class="{
            active: pagination.current_page == link,
            disabled: isNaN(link),
          }"
          class="pagination-item"


        >
          <a
            @click.prevent="goToPage(1)"
            class="pagination-link"
            :rel="getRelation(link)"
            href="#"
            v-if="link == 1"
          >
            {{ link }}
          </a>
          <a
            @click.prevent="goToPage(link)"
            class="pagination-link"
            :rel="getRelation(link)"
            :href="`?page=${link}`"
            v-else
          >
            {{ link }}
          </a>
        </li>
        <!-- <li :class="{ disabled: !next }" class="pagination-item">
          <a
            @click.prevent="goToPage(pagination.last_page)"
            class="pagination-link"
            :href="`?page=${pagination.last_page}`"
          >
            <svg width="14" height="22" viewBox="0 0 14 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.66699 19.3335L11.0003 11.0002L2.66699 2.66683" stroke="#063F5F" stroke-width="3" stroke-linecap="square"/>
            </svg>
          </a>
        </li> -->
        <li :class="{ disabled: !next }" class="pagination-item">
          <a href="#" @click.prevent="goToPage(next)" class="pagination-link">
            <svg width="14" height="22" viewBox="0 0 14 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.66699 19.3335L11.0003 11.0002L2.66699 2.66683" stroke="#063F5F" stroke-width="3" stroke-linecap="square"/>
            </svg>
          </a>
        </li>
      </ul>
      <!-- <div
        class="alert alert-danger card-m-5"
        v-if="pagination.total < 1 || pagination.from == null"
      >
        No Results Found
      </div> -->
    </div>
  </template>

  <script>
  export default {
    props: {
      pagination: {
        type: Object,
        required: true,
      },
      routeName: {
        type: String,
        required: true,
      },
      param: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        limit: 4,
        // q: {}
      };
    },

    computed: {
      pages() {
        let pages = [];
        for (let i = 1; i <= this.pagination.last_page; i++) {
          pages.push(i);
        }
        return pages;
      },
      links() {
        let first = [1, '...'],
          last = ['...', this.pagination.last_page],
          range = [];

        if (this.pagination.current_page <= this.limit) {
          range = this.range(1, this.limit + 1);
          return this.pagination.current_page + range.length <=
            this.pagination.last_page
            ? range.concat(last)
            : range;
        } else if (
          this.pagination.current_page >
          this.pagination.last_page - this.limit
        ) {
          range = this.range(
            this.pagination.last_page - this.limit,
            this.pagination.last_page
          );
          return this.pagination.current_page - range.length >= 1
            ? first.concat(range)
            : range;
        } else {
          range = this.range(
            this.pagination.current_page - Math.ceil(this.limit / 2),
            this.pagination.current_page + Math.ceil(this.limit / 2)
          );
          return first.concat(range).concat(last);
        }
      },
      next() {
        let next = this.pagination.current_page + 1;
        return next <= this.pagination.last_page ? next : false;
      },
      prev() {
        return this.pagination.current_page - 1;
      },
      show() {
        return this.pagination.last_page > 1;
      },
    },
    methods: {
      getRelation(link){
        if((this.pagination.current_page - 1) == link){
          return 'next'
        }
        else if((this.pagination.current_page  + 1 ) == link){
          return 'prev'
        }
        else if(this.pagination.current_page == link){
          return 'canonical'
        }else {
          return false;
        }
      },
      range(start, end) {
        let pages = [];

        for (let i = start - 1; i < end; i++) {
          if (this.pages[i]) {
            pages.push(this.pages[i]);
          }
        }
        return pages;
      },
      go(page) {
        if (isNaN(page)) {
          return;
        }
      },
      goToPage(link) {
        if(this.pagination.current_page == link) return;
        const query = { ...this.$route.query }
        query.page = link;

        let params = {};
        params[this.param.key] = this.param.value;
        this.$router.push({
          name: this.routeName,
          params: params,
          query: query,
        });
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        this.$emit('updateResult')
      },
    },
  };
  </script>


<style lang="scss" scoped>
    .pagination__content {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .pagination__body {
        margin: 0;
        padding: 0;

        list-style: none;
        display: flex;

        .pagination-item {
            margin-left: 4px;
            margin-right: 4px;

            &.active {
                .pagination-link {
                    pointer-events: none;
                    cursor: default;
                    background: #BEE7FB;
                }
            }
            &.disabled {
                .pagination-link {
                    pointer-events: none;
                    cursor: default;
                }
            }
        }

        .pagination-link {
            display: block;
            width: 48px;
            height: 48px;
            background: #E9F7FE;
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 27px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            color: #2C5B7D;
            text-decoration: none;
        }
    }
</style>

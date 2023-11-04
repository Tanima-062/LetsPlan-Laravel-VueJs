<template>
    <div class="view" style="padding-bottom: 10px">
        <div class="head">
            <h1 class="page-title">Anmeldungen</h1>

            <div class="control">
                <div class="wrapper d-flex w-100" style="gap: 32px">
                    <Search
                        placeholder="Nach Vor- Nachname, Geburtsdatum oder Wohnort des Kindes suchen"
                        @updateResult="getAnmeldungens()"
                        route="anmeldungen"
                        :style="{ width: isEditable ? '75%' : '100%' }"
                    />
                    <CreateButton
                        text="Neue Anmeldung hinzufügen"
                        target="/anmeldungen/create"
                        v-if="isEditable"
                    />
                </div>
                <div class="filters">
                    <div
                        class="wrapper d-flex align-items-center"
                        :class="{ 'bg-white': filter_opened }"
                    >
                        <FilterButton
                            @toggle="filter_opened = !filter_opened"
                        />

                        <div v-if="filter_opened" class="filter-menus">
                            <DateRangeFilter
                                @updateResult="getAnmeldungens()"
                            />

                            <SingleSelectFilter
                                :options="filterables.division.data"
                                column="division"
                                label="Einteilung"
                                @updateFilterable="updateFilterable('division')"
                                @updateResult="getAnmeldungens()"
                            />

                            <MultiSelectFilter
                                :options="filterables.preferred_playgroup.data"
                                column="preferred_playgroup"
                                label="Präferierte Spielgruppe"
                                @updateFilterable="
                                    updateFilterable('preferred_playgroup')
                                "
                                @updateResult="getAnmeldungens()"
                            />

                            <SingleSelectFilter
                                :options="filterables.commented.data"
                                column="commented"
                                label="Bemerkungen"
                                @updateFilterable="
                                    updateFilterable('commented')
                                "
                                @updateResult="getAnmeldungens()"
                            />

                            <SingleSelectFilter
                                :options="filterables.marked.data"
                                column="marked"
                                label="Markierung"
                                @updateFilterable="updateFilterable('marked')"
                                @updateResult="getAnmeldungens()"
                            />

                            <SingleSelectFilter
                                :options="filterables.status.data"
                                column="status"
                                label="Status"
                                @updateFilterable="updateFilterable('status')"
                                @updateResult="getAnmeldungens()"
                            />
                        </div>
                    </div>

                    <EntriesPerPage @updateResult="getAnmeldungens()" />
                </div>
            </div>
        </div>
        <div class="content" style="margin-bottom: 20px">
            <Table>
                <template #thead>
                    <div class="th" style="width: 10%">
                        <span>Name</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="child_name"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 18%">
                        <span>Präferierte Spielgruppe</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="preferred_playgroup"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 22%">
                        <span>Naheliegendste Spielgruppe</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="closest_playgroup"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 18%">
                        <span>Eingeteilte Spielgruppe</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="divided_playgroup"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 12%">
                        <span>Bemerkungen</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="note"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 10%">
                        <span>Status</span>
                        <Sort
                            style="margin-top: -7px"
                            columnName="status_id"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 5%">
                        <svg
                            width="28"
                            height="22"
                            viewBox="0 0 28 22"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12.1144 11.5313L12.9983 10.6474L12.9983 10.6474L12.1144 11.5313ZM15.8856 11.5313L15.0017 10.6474L15.0017 10.6474L15.8856 11.5313ZM3.33333 2.66699H24.6667V0.166992H3.33333V2.66699ZM24.75 2.75033V18.7503H27.25V2.75033H24.75ZM24.6667 18.8337H3.33333V21.3337H24.6667V18.8337ZM3.25 18.7503V2.75033H0.75V18.7503H3.25ZM3.33333 18.8337C3.28731 18.8337 3.25 18.7963 3.25 18.7503H0.75C0.75 20.1771 1.9066 21.3337 3.33333 21.3337V18.8337ZM24.75 18.7503C24.75 18.7963 24.7127 18.8337 24.6667 18.8337V21.3337C26.0934 21.3337 27.25 20.1771 27.25 18.7503H24.75ZM3.33333 0.166992C2.62031 0.166992 1.97276 0.457511 1.50664 0.923633L3.27441 2.6914C3.28286 2.68295 3.29221 2.67686 3.30128 2.67303C3.30951 2.66955 3.31952 2.66699 3.33333 2.66699V0.166992ZM1.50664 0.923633C1.04052 1.38976 0.75 2.0373 0.75 2.75033H3.25C3.25 2.73651 3.25255 2.7265 3.25604 2.71827C3.25987 2.70921 3.26596 2.69985 3.27441 2.6914L1.50664 0.923633ZM24.6667 2.66699C24.6805 2.66699 24.6905 2.66955 24.6987 2.67303C24.7078 2.67686 24.7171 2.68295 24.7256 2.6914L26.4934 0.923635C26.0272 0.457508 25.3797 0.166992 24.6667 0.166992V2.66699ZM24.7256 2.6914C24.734 2.69985 24.7401 2.70921 24.744 2.71827C24.7474 2.7265 24.75 2.73651 24.75 2.75033H27.25C27.25 2.0373 26.9595 1.38976 26.4934 0.923635L24.7256 2.6914ZM11.2305 12.4152C12.7601 13.9447 15.2399 13.9448 16.7695 12.4152L15.0017 10.6474C14.4485 11.2007 13.5515 11.2007 12.9983 10.6474L11.2305 12.4152ZM1.50664 2.6914L11.2305 12.4152L12.9983 10.6474L3.27441 0.92363L1.50664 2.6914ZM16.7695 12.4152L26.4934 2.6914L24.7256 0.92363L15.0017 10.6474L16.7695 12.4152Z"
                                fill="#42BEAF"
                            />
                        </svg>
                        <Sort
                            style="margin-top: -7px"
                            columnName="marked"
                            @updateResult="getAnmeldungens()"
                        />
                    </div>
                    <div class="th" style="width: 5%">
                        <span></span>
                    </div>
                </template>
                <template #tbody>
                    <TableBodyRow
                        v-for="register in anmeldungens.data"
                        :register="register"
                        :key="register.id"
                        @updateResult="getAnmeldungens()"
                    />
                </template>

                <template #footer> </template>
            </Table>
        </div>

        <Pagination
            v-if="anmeldungens.meta"
            :pagination="anmeldungens.meta"
            routeName="anmeldungen"
            :param="{ key: '', value: '' }"
            @updateResult="getAnmeldungens()"
            style="margin-left: 20px"
        />
        <!-- Modals -->
        <ConfirmationModal />
        <EmailModal />
    </div>
</template>

<script>
import { codes } from "../../../contryCodes";
import { mask } from "vue-the-mask";
import AnmeldungenTable from "./AnmeldungenTable";
import axios from "../../axios";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import PlayGroupPlanning from "../../shared/PlayGroupPlanning";
import LatestNotifications from "../../shared/LatestNotifications";
import phoneNumberFormater from "../../mixins/phoneNumberFormaterMixin";
import getLanguages from "../../mixins/getLanguagesMixin";
import getNationalities from "../../mixins/getNationalitiesMixin";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import throttle from "lodash/throttle";
import Search from "./components/Search.vue";
import CreateButton from "./components/CreateButton.vue";
import FilterButton from "./components/FilterButton.vue";
import EntriesPerPage from "./components/EntriesPerPage.vue";
import Table from "./components/Table.vue";
import Sort from "./components/Sort.vue";
import TableBodyRow from "./components/TableBodyRow.vue";
import MultiSelectFilter from "./components/Filter/MultiSelectFilter.vue";
import SingleSelectFilter from "./components/Filter/SingleSelectFilter.vue";
import DateRangeFilter from "./components/Filter/DateRangeFilter.vue";
import ConfirmationModal from "./components/ConfirmationModal.vue";
import EmailModal from "./components/EmailModal.vue";
import Pagination from "./components/Pagination.vue";
import { mapGetters } from "vuex";

export default {
    name: "Anmeldungen",
    directives: { mask },
    mixins: [
        phoneNumberFormater,
        getLanguages,
        getNationalities,
        flushMessagesMixin,
    ],
    components: {
        AnmeldungenTable,
        VueGoogleAutocomplete,
        PlayGroupPlanning,
        LatestNotifications,
        Search,
        CreateButton,
        FilterButton,
        EntriesPerPage,
        Table,
        Sort,
        TableBodyRow,
        MultiSelectFilter,
        SingleSelectFilter,
        DateRangeFilter,
        ConfirmationModal,
        EmailModal,
        Pagination,
    },
    data() {
        return {
            role: localStorage.getItem("role"),
            anmeldungens: [],
            spielgruppens: [],

            filter_opened: false,
            filterables: {
                preferred_playgroup: {
                    data: [],
                },
                commented: {
                    data: [],
                },
                division: {
                    data: [],
                },
                status: {
                    data: [],
                },
                marked: {
                    data: [],
                },
            },
        };
    },

    computed: {
        ...mapGetters(["hasRole"]),

        isEditable() {
            return this.hasRole([window.PLAYGROUP_ADMIN, window.SUPER_ADMIN]);
        },
    },

    created() {
        this.getLanguages();
        this.getAnmeldungens();
    },
    methods: {
        getAnmeldungens() {
            const params = location.search;
            axios
                .get(`/api/anmeldungens${params}`)
                .then((res) => {
                    this.anmeldungens = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        updateFilterable(menu) {
            const params =
                location.search.length > 0
                    ? `${location.search}&column=${menu}`
                    : `?column=${menu}`;
            axios
                .get(`/api/anmeldungens/filterable${params}`)
                .then((res) => {
                    if(menu == 'preferred_playgroup'){
                        res.data.splice(0, 0, { value: 'keine', label: 'Keine' })
                    }
                    this.filterables[menu].data = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.view {
    background-color: white !important;
    min-height: 100vh;

    .head {
        .page-title {
            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 22px;
            line-height: 33px;
            color: #063f5f;
            margin-top: 25px;
        }

        .control {
            background-color: #e9f7fe;
            padding: 16px 16px 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;

            .filters {
                display: flex;
                justify-content: space-between;
                align-items: center;

                .filter-menus {
                    display: flex;
                    width: 100%;
                    padding: 0px 37px 0px 24px;
                    align-items: center;
                    gap: 24px;
                }
            }
        }
    }
}
</style>

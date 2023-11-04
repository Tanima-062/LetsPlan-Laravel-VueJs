<template>
    <div class="page">
        <div class="head">
            <h1 class="page-title">Spielgruppen</h1>

            <div class="control">
                <div class="wrapper d-flex w-100" style="gap: 32px">
                    <Search
                        placeholder="Nach Spielgruppennamen, Postleitzahl oder Ort der Spielgruppe suchen"
                        @updateResult="getSpielgruppens()"
                        route="spielgruppen"
                    />
                    <CreateButton
                        v-if="role == 'admin' || role=='super_admin'"
                        text="Neue Spielgruppen hinzufügen"
                        target="/spielgruppen/create"
                    />
                </div>
                <div class="filters">
                    <div
                        class="wrapper d-flex align-items-center"
                        :class="{ 'bg-white': filter_opened }"
                        style="gap: 20px; width: 85%"
                    >
                        <FilterButton
                            @toggle="filter_opened = !filter_opened"
                        />
                        <div
                            v-if="filter_opened"
                            class="w-100 d-flex"
                            style="gap: 20px"
                        >
                            <div style="width: 15%">
                                <MultiSelectFilter
                                    :options="filterables.city.data"
                                    column="city"
                                    label="Ort"
                                    @updateFilterable="updateFilterable('city')"
                                    @updateResult="getSpielgruppens()"
                                />
                            </div>
                            <div style="width: 20%">
                                <MultiSelectFilter
                                    :options="filterables.time.data"
                                    column="time"
                                    label="Öffnungszeiten"
                                    @updateFilterable="updateFilterable('time')"
                                    @updateResult="getSpielgruppens()"
                                />
                            </div>
                            <div style="width: 20%">
                                <MultiSelectFilter
                                    :options="filterables.lead.data"
                                    column="lead"
                                    label="Spielgruppenleiter/in"
                                    @updateFilterable="updateFilterable('lead')"
                                    @updateResult="getSpielgruppens()"
                                />
                            </div>
                            <div style="width: 25%">
                                <MultiSelectFilter
                                    :options="filterables.assistant.data"
                                    column="assistant"
                                    label="Spielgruppen Assistent/in"
                                    @updateFilterable="
                                        updateFilterable('assistant')
                                    "
                                    @updateResult="getSpielgruppens()"
                                />
                            </div>
                            <div style="width: 20%">
                                <MultiSelectFilter
                                    :options="filterables.status.data"
                                    column="status"
                                    label="Status"
                                    @updateFilterable="
                                        updateFilterable('status')
                                    "
                                    @updateResult="getSpielgruppens()"
                                />
                            </div>
                        </div>
                    </div>
                    <EntriesPerPage @updateResult="getSpielgruppens()"/>
                </div>
            </div>
        </div>
        <div class="body mb-4">
            <Table>
                <template #thead>
                    <div class="th" style="width: 25%">
                        <span>Bild</span>
                    </div>
                    <div class="th" style="width: 30%">
                        <span>Name</span>
                        <Sort
                            style="margin-top:-7px;"
                            columnName="name"
                            @updateResult="getSpielgruppens()"
                        />
                    </div>
                    <div class="th" style="width: 35%">
                        <span>Adresse</span>
                        <Sort
                            style="margin-top:-7px;"
                            columnName="street_name"
                            @updateResult="getSpielgruppens()"
                        />
                    </div>
                    <div class="th" style="width: 5%">
                        <span>Status</span>
                        <Sort
                            style="margin-top:-7px;"
                            columnName="status_id"
                            @updateResult="getSpielgruppens()"
                        />
                    </div>
                    <div class="th" style="width: 5%">
                        <span></span>
                    </div>
                </template>
                <template #tbody>
                    <TableBodyRow
                        v-for="spielgruppen in spielgruppens.data"
                        :spielgruppen="spielgruppen"
                        :key="spielgruppen.id"
                        :role="role"
                        @updateResult="getSpielgruppens()"
                    />
                </template>
            </Table>
        </div>
        <Pagination
            v-if="spielgruppens.meta"
            :pagination="spielgruppens.meta"
            routeName="spielgruppen"
            :param="{ key: '', value: '' }"
            @updateResult="getSpielgruppens()"
        />
    </div>
</template>

<script>
import Search from "./components/Search.vue";
import CreateButton from "./components/CreateButton.vue";
import FilterButton from "./components/FilterButton.vue";
import EntriesPerPage from "./components/EntriesPerPage.vue";
import Table from "./components/Table.vue";
import Sort from "./components/Sort.vue";
import TableBodyRow from "./components/TableBodyRow.vue";
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import MultiSelectFilter from "./components/Filter/MultiSelectFilter.vue";
import Pagination from "./components/Pagination.vue";

export default {
    name: "Spielgruppen",
    mixins: [flushMessagesMixin],
    components: {
        Search,
        CreateButton,
        FilterButton,
        EntriesPerPage,
        Table,
        Sort,
        TableBodyRow,
        MultiSelectFilter,
        Pagination,
    },
    data() {
        return {
            role: localStorage.getItem("role"),
            filter_opened: false,
            spielgruppens: [],
            times: [],
            filterables: {
                lead: {
                    data: [],
                },
                assistant: {
                    data: [],
                },
                city: {
                    data: [],
                },
                status: {
                    data: [],
                },
                time: {
                    data: [],
                },
            },
        };
    },
    mounted() {
        this.getSpielgruppens();
    },
    methods: {
        getSpielgruppens(page = 1) {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/spielgruppen?${queryString}`)
                .then((res) => {
                    this.spielgruppens = res.data;
                })
                .catch((err) => {});
        },
        updateFilterable(menu) {
            const params =
                location.search.length > 0
                    ? `${location.search}&column=${menu}`
                    : `?column=${menu}`;
            axios
                .get(`/api/spielgruppens-filterable${params}`)
                .then((res) => {
                    this.filterables[menu].data = res.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
};
</script>

<style lang="scss">
.page {
    background-color: white;
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
            }
        }
    }
}
</style>

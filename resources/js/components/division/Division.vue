<template>
    <div>
        <div class="planning">
            <div class="planning__header">
                <h3
                    style="
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 22px;
                        line-height: 33px;
                        color: #063f5f;
                    "
                >
                    Einteilungen
                </h3>
            </div>
            <div class="planning__content">
                <div class="planning__content--top">
                    <Search
                        routeName="division"
                        @updateResult="getDivisions()"
                        placeholder="Nach Einteilungsnamen suchen"
                    />
                    <AddNewButton
                        v-if="role == 'admin' || role=='super_admin'"
                        label="Neue Einteilung hinzufügen"
                        routeName="division_add"
                    />
                </div>
                <div
                    class="planning__content--filters"
                    style="margin-top: 12px"
                >
                    <!-- <Status :statuses="statuses" :status_id="status_id"  :disabled="role !== 'admin'"   /> -->
                    <EntriesPerPage
                        @updateResult="getDivisions()"
                        style="margin-left: auto"
                        routeName="division"
                    />
                </div>
                <div class="planning__content--body">
                    <Table>
                        <template #thead>
                            <Sort
                                label="Name"
                                routeName="division"
                                style="width: 15%"
                                columnName="name"
                                @updateResult="getDivisions()"
                            />
                            <Sort
                                label="Anmeldefrist"
                                routeName="division"
                                style="width: 22%"
                                columnName="registration_start_date"
                                @updateResult="getDivisions()"
                            />
                            <Sort
                                label="Spielgruppen Zeitraum"
                                routeName="division"
                                style="width: 23%"
                                columnName="playgroup_start_date"
                                @updateResult="getDivisions()"
                            />
                            <Sort
                                label="Datum Elterninformation"
                                routeName="division"
                                style="width: 28%"
                                columnName="date_parent"
                                @updateResult="getDivisions()"
                            />
                            <Sort
                                label="Status "
                                routeName="division"
                                style="width: 10%"
                                columnName="status_id"
                                @updateResult="getDivisions()"
                            />
                            <div
                                class="th"
                                style="width: 2%; margin-left: auto"
                            ></div>
                        </template>
                        <template #tbody>
                            <TableBodyRow
                                v-for="division in divisions.data"
                                :division="division"
                                :key="division.id"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <Pagination
            v-if="divisions.meta"
            :pagination="divisions.meta"
            routeName="division"
            :param="{ key: '', value: '' }"
            @updateResult="getDivisions()"
        />
        <ConfirmModal @divisoin-deleted="getDivisions" />
        <EditConfirmModal />
        <Notification />
    </div>
</template>

<script>
import axios from "../../axios";

import Notification from "./Components/Notification.vue";
import AddNewButton from "./Components/AddNewButton.vue";
import Search from "./Components/Search.vue";
import EntriesPerPage from "./Components/EntriesPerPage.vue";
import Table from "./Components/Table/Table.vue";
import Sort from "./Components/Table/Sort";
import TableBodyRow from "./Components/Table/TableBodyRow.vue";
import Pagination from "./Components/Pagination.vue";
import ConfirmModal from "./Components/ConfirmModal.vue";
import EditConfirmModal from "./Components/EditConfirmModal.vue";

export default {
    name: "DivisionOverview",
    components: {
        Notification,
        AddNewButton,
        Search,
        EntriesPerPage,
        Table,
        Sort,
        TableBodyRow,
        Pagination,
        ConfirmModal,
        EditConfirmModal,
    },
    data() {
        return {
            role: localStorage.getItem("role"),
            divisions: [],
            rowId: "",
        };
    },
    created() {
        this.getDivisions();
    },
    methods: {
        getDivisions(page = 1) {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/division?${queryString}`)
                .then((res) => {
                    this.divisions = res.data;
                    console.log(res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },

    watch: {},
};
</script>

<style lang="scss" scoped>
.plannung {
    margin-top: 0px;
}
.planning {
    padding: 25px 0px;

    .planning__content {
        height: 100vh;
        background: #e9f7fe;
        padding: 15px;
        height: auto;

        .planning__content--top {
            // .row {
            //     --bs-gutter-x: 0;
            //     gap: 32px;
            // }

            display: flex;
            gap: 20px;
        }

        .planning__content--filters {
            display: flex;
            // gap: 32px;
            align-items: center;
            .filters {
                // width: 1145px;
                width: 75%;
            }
        }
        .planning__content--body {
            // display: flex;
            // gap: 32px;
        }
    }
}

.row {
    --bs-gutter-x: 0;
}

.plannung {
    margin-bottom: 0px;
}
</style>

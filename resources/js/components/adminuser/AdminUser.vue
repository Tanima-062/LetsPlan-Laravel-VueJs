<template>
    <div>
        <div class="page">
            <div class="head">
                <h1 class="page-title">Spielgruppen Admins</h1>
                <div class="control">
                    <div class="wrapper d-flex w-100" style="gap: 32px">
                        <Search routeName="adminuser" @updateResult="getAdmins()" placeholder="Nach Unternehmensnamen, Vor- oder Nachnamen des Admins suchen" column="query" />
                        <AddNewButton v-if="role == 'admin' || role=='super_admin'" text="Neuer Admin hinzufügen" target="/adminuser/add" />
                    </div>
                    <div class="filters">
                        <div class="wrapper d-flex align-items-center" :class="{ 'bg-white': filter_opened }" style="gap: 20px; width: 85%">
                            <FilterButton @toggle="filter_opened = !filter_opened" />
                            <div v-if="filter_opened" class="w-100 d-flex" style="gap: 20px">
                                <div style="width: 50%">
                                    <MultiSelectFilter column="company_id" label="Unternehmen" @updateResult="getAdmins()" routeName="adminuser" apiPath="adminuser-filterable" />
                                </div>
                                <div style="width: 20%">
                                    <!-- <MultiSelectFilter
                                            :options="filterables.status.data"
                                            column="status"
                                            label="Status"
                                            @updateFilterable="
                                                updateFilterable('status')
                                            "
                                            @updateResult="getAdmins()"
                                        /> -->
                                    <MultiSelectFilter column="status_id" label="Status" @updateResult="getAdmins()" routeName="adminuser" apiPath="adminuser-filterable" />
                                </div>
                            </div>
                        </div>
                        <EntriesPerPage @updateResult="getAdmins()" style="margin-left: auto" routeName="adminuser" />
                    </div>
                </div>
            </div>
            <div class="body mb-4">
                <Table>
                    <template #thead>
                            <div class="th" style="width: 15%">
                                <span>Erstellungsdatum</span>
                                <Sort
                                    columnName="created_at"
                                    @updateResult="getAdmins()"
                                />
                            </div>
                            <div class="th" style="width: 15%">
                                <span>ID</span>
                                <Sort columnName="id" @updateResult="getAdmins()" />
                            </div>
                            <div class="th" style="width: 15%">
                                <span>Unternehmen</span>
                                <Sort
                                    columnName="company"
                                    @updateResult="getAdmins()"
                                />
                            </div>
                            <div class="th" style="width: 20%">
                                <span>Name</span>
                                <Sort
                                    columnName="first_name"
                                    @updateResult="getAdmins()"
                                />
                            </div>
                            <div class="th" style="width: 25%">
                                <span>E-Mail Adresse</span>
                                <Sort
                                    columnName="email"
                                    @updateResult="getAdmins()"
                                />
                            </div>
                            <div class="th" style="width: 8%">
                                <span>Status</span>
                                <Sort
                                    columnName="status_id"
                                    @updateResult="getAdmins()"
                                />
                            </div>
                            <div class="th" style="width: 2%">
                                <span></span>
                            </div>
</template>

<template #tbody>
    <TableBodyRow v-for="adminuser in admins.data" :adminuser="adminuser" :key="adminuser.id" @updateResult="getAdmins()" />
</template>
                </Table>
            </div>
        </div>
        <Pagination
            v-if="admins.meta"
            :pagination="admins.meta"
            routeName="adminuser"
            :param="{ key: '', value: '' }"
            @updateResult="getAdmins()"
        />
        <ConfirmModal />
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
import MultiSelectFilter from "./Components/Filter/MultiSelectFilter.vue";
import FilterButton from "./Components/FilterButton.vue";

export default {
    name: "adminuserOverview",
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
        MultiSelectFilter,
        FilterButton,
    },
    data() {
        return {
            role: localStorage.getItem("role"),
            filter_opened: false,
            admins: [],
            filterables: {
                status: {
                    data: [],
                },
                company: {
                    data: [],
                },
            },
        };
    },
    mounted() {
        this.getAdmins();
    },
    methods: {
        getAdmins(page = 1) {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/adminuser?${queryString}`)
                .then((res) => {
                    this.admins = res.data;
                })
                .catch((err) => {});
        },
        updateFilterable(menu) {
            const params =
                location.search.length > 0 ?
                `${location.search}&column=${menu}` :
                `?column=${menu}`;
            axios
                .get(`/api/adminuser-filterable${params}`)
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

<style lang="scss" scoped>
.page {
    background-color: white; // min-height: 100vh;
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
            display: flex; // gap: 32px;
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

.page {
    background-color: white; // min-height: 100vh;
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

.letsplan-table {
    width: calc(100%) !important;
    margin-left: 0px !important;
}

.page {
    min-height: initial;
}
</style>

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
                    Benutzer
                </h3>
            </div>
            <div class="planning__content">
                <div class="planning__content--top">
                    <Search routeName="user" @updateResult="getUsers()" />
                    <AddNewUserButton />
                </div>
                <div
                    class="planning__content--filters"
                    style="margin-top: 12px"
                >
                    <Filters>
                        <!-- <DaateRangeFilter routeName="plannung" /> -->
                        <div style="width: 50%">
                            <MultiSelectFilter
                                column="role_id"
                                label="Benutzertyp"
                                @updateResult="getUsers()"
                                routeName="user"
                                apiPath="benutzer-filterable"
                            />
                        </div>
                        <div style="width: 50%">
                            <MultiSelectFilter
                                column="status_id"
                                label="Status"
                                @updateResult="getUsers()"
                                routeName="user"
                                apiPath="benutzer-filterable"
                            />
                        </div>
                    </Filters>
                    <!-- <Status :statuses="statuses" :status_id="status_id"  :disabled="role !== 'admin'"   /> -->
                    <EntriesPerPage
                        @updateResult="getUsers()"
                        style="margin-left: auto"
                        routeName="user"
                    />
                </div>
                <div class="planning__content--body">
                    <Table>
                        <template #thead>
                            <Sort
                                label="Profilbild"
                                routeName="user"
                                style="width: 13%"
                                columnName="image"
                                @updateResult="getUsers()"
                            />
                            <Sort
                                label="Name"
                                routeName="user"
                                style="width: 20%"
                                columnName="first_name"
                                @updateResult="getUsers()"
                            />
                            <Sort
                                label="Benutzertyp"
                                routeName="user"
                                style="width: 20%"
                                columnName="role_id"
                                @updateResult="getUsers()"
                            />
                            <Sort
                                label="E-Mail"
                                routeName="user"
                                style="width: 30%"
                                columnName="email"
                                @updateResult="getUsers()"
                            />
                            <Sort
                                label="Telefonnummer "
                                routeName="user"
                                style="width: 15%"
                                columnName="phone_number"
                                @updateResult="getUsers()"
                            />
                            <Sort
                                label="Status "
                                routeName="user"
                                style="width: 5%"
                                columnName="status_id"
                                @updateResult="getUsers()"
                            />
                            <div
                                class="th"
                                style="width: 2%; margin-left: auto"
                            ></div>
                        </template>
                        <template #tbody>
                            <TableBodyRow
                                v-for="user in users.data"
                                :user="user"
                                :key="user.id"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <Pagination
            v-if="users.meta"
            :pagination="users.meta"
            routeName="user"
            :param="{ key: '', value: '' }"
            @updateResult="getUsers()"
        />
        <ConfirmModal />
        <Notification />
    </div>
</template>

<script>
import axios from "../../axios";
import throttle from "lodash/throttle";

import Notification from "./Components/Notification.vue";
import Filters from "./Components/Filter/Filters.vue";
import AddNewUserButton from "./Components/AddNewUserButton.vue";

import Search from "./Components/Search.vue";
import MultiSelectFilter from "./Components/Filter/MultiSelectFilter.vue";
import EntriesPerPage from "./Components/EntriesPerPage.vue";
import Table from "./Components/Table/Table.vue";
import Sort from "./Components/Table/Sort";
import TableBodyRow from "./Components/Table/TableBodyRow.vue";
import Pagination from "./Components/Pagination.vue";
import ConfirmModal from "./Components/ConfirmModal.vue";

export default {
    name: "UserOverview",
    components: {
        Notification,
        // RegistrationView,
        Filters,
        MultiSelectFilter,
        // DaateRangeFilter
        AddNewUserButton,
        Search,
        EntriesPerPage,
        Table,
        Sort,
        TableBodyRow,
        Pagination,
        ConfirmModal,
    },
    data() {
        return {
            users: [],
        };
    },
    created() {
        this.getUsers();
    },
    methods: {
        getUsers(page = 1) {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/benutzer?${queryString}`)
                .then((res) => {
                    this.users = res.data;
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
            margin-bottom: 12px;
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

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
                    Unternehmen
                </h3>
            </div>
            <div class="planning__content">
                <div class="planning__content--top">
                    <Search
                        routeName="company"
                        @updateResult="getCompanies()"
                        placeholder="Nach Unternehmensnamen suchen"
                    />
                    <AddNewButton
                        label="Neues Unternehmen hinzufügen"
                        routeName="company_add"
                    />
                </div>
                <div
                    class="planning__content--filters"
                    style="margin-top: 22px; margin-bottom: 22px"
                >
                    <!-- <Status :statuses="statuses" :status_id="status_id"  :disabled="role !== 'admin'"   /> -->
                    <EntriesPerPage
                        @updateResult="getCompanies()"
                        style="margin-left: auto"
                        routeName="company"
                    />
                </div>
                <div class="planning__content--body">
                    <Table>
                        <template #thead>
                            <Sort
                                label="Erstellungsdatum"
                                routeName="company"
                                style="width: 15%"
                                columnName="created_at"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="ID"
                                routeName="company"
                                style="width: 8%"
                                columnName="uniq_id"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="Name"
                                routeName="company"
                                style="width: 20%"
                                columnName="name"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="Kontaktperson"
                                routeName="company"
                                style="width: 20%"
                                columnName="first_name"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="Telefonnummer"
                                routeName="company"
                                style="width: 15%"
                                columnName="telephone"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="E-Mail Adresse"
                                routeName="company"
                                style="width: 20%"
                                columnName="email"
                                @updateResult="getCompanies()"
                            />
                            <Sort
                                label="Status "
                                routeName="company"
                                style="width: 5%"
                                columnName="status"
                                @updateResult="getCompanies()"
                            />
                            <div
                                class="th"
                                style="width: 2%; margin-left: auto"
                            ></div>
                        </template>
                        <template #tbody>
                            <TableBodyRow
                                v-for="company in companies.data"
                                :company="company"
                                :key="company.id"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <Pagination
            v-if="companies.meta"
            :pagination="companies.meta"
            routeName="company"
            :param="{ key: '', value: '' }"
            @updateResult="getCompanies()"
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

export default {
    name: "CompanyOverview",
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
    },
    data() {
        return {
            companies: [],
        };
    },
    created() {
        this.getCompanies();
    },
    methods: {
        getCompanies(page = 1) {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/company?${queryString}`)
                .then((res) => {
                    this.companies = res.data;
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

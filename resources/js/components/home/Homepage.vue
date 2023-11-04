<template>
    <div>
        <h2
            style="
                font-family: 'Nexa';

                font-style: normal;

                font-weight: 700;

                font-size: 22px;

                line-height: 33px;

                color: #063f5f;

                margin-top: 25px;

                margin-bottom: 0;
            "
        >
            Dashboard
        </h2>

        <div class="parent">
            <div class="filters" style="width: 35%">
                <div
                    class="wrapper d-flex align-items-center"
                    :class="{ 'bg-white': filter_opened }"
                    style="width: 85%"
                >
                    <FilterButton @toggle="filter_opened = !filter_opened" />

                    <div
                        v-if="filter_opened"
                        class="w-100 d-flex"
                        style="gap: 20px"
                    >
                        <div class="w-100 d-flex justify-center">
                            <MultiSelectFilter
                                :options="filterables.division.data"
                                column="division"
                                label="Einteilung"
                                @updateFilterable="updateFilterable('division')"
                                @updateResult="getDivisions()"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-11">
                <div class="flex row my-3">
                    <div class="col-4">
                        <statistic-card
                            :number="total_child_registrations"
                            category="Kinder"
                            linkText="Anmeldungen ansehen"
                            linkName="anmeldungen"
                            type="1"
                        />
                    </div>

                    <div class="col-4">
                        <statistic-card
                            :number="total_spielgruppen_half_days"
                            category="Spielgruppen Halbtage"
                            linkText="Planung ansehen"
                            linkName="plannung"
                            type="2"
                        />
                    </div>

                    <div class="col-4">
                        <statistic-card
                            :number="total_spielgruppens"
                            category="Spielgruppen"
                            linkText="Spielgruppen ansehen"
                            linkName="spielgruppen"
                            type="3"
                        />
                    </div>
                </div>

                <div class="flex row">
                    <div class="col-4">
                        <div class="h-100 bg-white">
                            <ChartColumn
                                v-if="load"
                                :data="
                                    registrations_chart_data.registrations_count_per_condition
                                "
                                :label="
                                    registrations_chart_data.registrations_condition_label
                                "
                                :colors="
                                    registrations_chart_data.registrations_condition_label_color
                                "
                                :textColors="registrations_chart_data.registrations_condition_text_color"
                                :showPercentage="false"
                            />
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="h-100 bg-white">
                            <ChartColumn
                                v-if="load"
                                title="Nationalitäten"
                                :label="nationalities_chart_data.nations"
                                :data="
                                    nationalities_chart_data.registration_counts
                                "
                            />
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="h-100 bg-white">
                            <ChartColumn
                                v-if="load"
                                title="Muttersprachen"
                                :label="mother_toungs_chart_data.mother_tongus"
                                :data="
                                    mother_toungs_chart_data.registration_counts
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";

import ChartColumn from "./ChartColumn.vue";
import MultiSelectFilter from "./components/Filter/MultiSelectFilter.vue";
import FilterButton from "./components/FilterButton.vue";
import DownArrowIconVue from "./components/DownArrowIcon.vue";
import Checkbox from "./components/Checkbox.vue";

export default {
    name: "Homepage",
    components: {
        ChartColumn,
        MultiSelectFilter,
        FilterButton,
        DownArrowIconVue,
        Checkbox,
    },
    created() {
        this.getLoggedInUser();
        // this.$store.dispatch("getDashboardData");
    },
    data() {
        return {
            filter_opened: false,
            total_child_registrations: 0,
            total_spielgruppen_half_days: 0,
            total_spielgruppens: 0,
            load: false,
            registrations_chart_data: {},
            nationalities_chart_data: {},
            mother_toungs_chart_data: {},
            filterables: {
                division: {
                    data: [],
                },
            },
        };
    },
    mounted() {
        this.getDivisions();
    },
    methods: {
        getLoggedInUser() {
            axios
                .get("/api/user")
                .then((res) => {
                    this.$store.dispatch("setUser", res.data.data);
                    localStorage.setItem("role", res.data.data.role_name);

                    console.log(res.data.data.role_id);
                    if (res.data.data.role_id == 4) {
                        this.$router.push({ name: "company" });
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async getDivisions() {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();
            axios
                .get(`/api/startseite?${queryString}`)
                .then((res) => {
                    this.total_child_registrations =
                        res.data.total_child_registrations;
                    this.total_spielgruppen_half_days =
                        res.data.total_spielgruppen_half_days;
                    this.total_spielgruppens = res.data.total_spielgruppens;

                    //first pie chart data
                    this.registrations_chart_data =
                        res.data.registrations_chart_data;
                    this.nationalities_chart_data =
                        res.data.nationalities_chart_data;
                    this.mother_toungs_chart_data =
                        res.data.mother_toungs_chart_data;

                    this.load = true;
                })
                .catch((err) => {});
        },
        updateFilterable(menu) {
            console.log(menu);
            const params =
                location.search.length > 0
                    ? `${location.search}&column=${menu}`
                    : `?column=${menu}`;
            axios
                .get(`/api/startseite-filterable${params}`)
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
.parent {
    background: #e9f7fe;
    padding: 25px 16px;
}
</style>

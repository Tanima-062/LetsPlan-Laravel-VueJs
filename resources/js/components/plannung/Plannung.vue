<template>
    <div class="plannung">
        <!-- <div class="px-view mx-auto flex justify-between items-center mb-3">
            <div>
                <select v-model="selected_playgroup" class="first-selection form-control mr-3">
                    <option :value="0">Nach Standort filtern</option>
                    <option v-for="item in planungsFilter" :value="item.id" :key="item.id">{{item.name}}</option>
                </select>
                <select class="first-selection form-control">
                    <option selected>August 2022</option>
                </select>
            </div>
            <div class="flex justify-between card plannung-card">
                <p style="margin-left: 15px;">Einteilung August 2022</p>
                <label class="custom-select">
                    <select :disabled="role !== 'admin'"  :style="{background:background, fontSize:'12px'}"
                            v-model="status_id"
                            class="form-control form-input-bordered status-select">
                        <option style="font-size: 12px" v-for="(status,index) in statuses" :key="index"
                                :value="status.status_id">
                            {{ status.status_name }}
                        </option>
                    </select>
                </label>
            </div>
        </div>
        <hr class="horizontal-line"/>
        <div style="margin-bottom:40px;" class="row flex justify-between px-view mx-auto bottom-drags">
            <draggable
                style="overflow-x: auto;gap: 40px; width: 100%;min-height: 300px"
                class="row flex justify-between"
                v-model="plannungsHeader"
                group="parents"
                @start="drag=true"
                @end="drag=false"
                :move="allowMovedElement"
            >
                <div v-for="item in plannungsHeader" :key="item.id" class="plannung-cols-6">
                    <PlannungCardItem :plannung="item"></PlannungCardItem>
                </div>
            </draggable>
        </div> -->

        <!-- <hr class="horizontal-line"/>
        <div style="margin-top:40px;" class="px-view mx-auto">
            <div class="w-full supervisor-section ">
                <draggable
                    class="flex justify-between  flex-wrap"
                    v-model="plannungs"
                    group="parents"
                    @start="drag=true"
                    @end="drag=false"
                    @change="checkMove"
                    :move="allowMovedElement"
                >
                    <div v-for="item in plannungs" :key="item.id" class="plannung-width-348">
                        <PlannungCardItem class="plannung-second-block" :plannung="item"></PlannungCardItem>
                    </div>
                </draggable>
            </div>

        </div> -->

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
                    Planung
                </h3>
            </div>
            <div class="planning__content">
                <div class="planning__content--top">
                    <Search
                        routeName="plannung"
                        @updateResult="getPlannung()"
                    />
                    <Export />
                </div>
                <div
                    class="planning__content--filters"
                    style="margin-top: 12px"
                >
                    <Filters>
                        <!-- <DaateRangeFilter routeName="plannung" /> -->
                        <div>
                            <MultiSelectFilter
                                column="playgroup"
                                label="Spielgruppe"
                                @updateResult="getPlannung()"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                        <div>
                            <!-- <MultiSelectFilter
                                    column="classification"
                                    label="Einteilung"
                                    @updateResult="getPlannung()"
                                    routeName="plannung"
                                    apiPath="planung/filterable"
                                /> -->
                            <SingleSelectFilter
                                column="classification"
                                label="Einteilung"
                                @updateResult="changeDivision"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                        <div>
                            <MultiSelectFilter
                                column="playgroup_leader"
                                label="Spielgruppenleiter/in"
                                @updateResult="getPlannung()"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                        <div>
                            <MultiSelectFilter
                                column="assistant"
                                label="Assistent/in"
                                @updateResult="getPlannung()"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                        <div>
                            <MultiSelectFilter
                                column="child"
                                label="Kind"
                                @updateResult="getPlannung()"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                        <div>
                            <MultiSelectFilter
                                column="marking"
                                label="Markierung"
                                @updateResult="getPlannung()"
                                routeName="plannung"
                                apiPath="planung/filterable"
                            />
                        </div>
                    </Filters>
                    <Status
                        :statuses="statuses"
                        :status_id="status_id"
                        :disabled="false"
                        v-if="user && user.role_id ==1"
                    />
                </div>
                <div class="planning__content--body">
                    <!-- <div style="margin-bottom:40px;" class="row flex justify-between px-view mx-auto bottom-drags">
                    </div> -->
                    <!-- <div style="width: 100%">
                        <draggable
                            style="
                                flex-wrap: wrap;
                                width: 100%;
                                min-height: 300px;
                            "
                            class="flex justify-between"
                            v-model="plannungsHeader"
                            group="parents"
                            @start="drag = true"
                            @end="drag = false"
                            :move="allowMovedElement"
                        >
                            <PlanningCardItemVue
                                v-for="item in plannungsHeader"
                                :key="item.id"
                                :plannung="item"
                            ></PlanningCardItemVue>
                        </draggable>
                    </div> -->
                    <!-- <hr style="margin: 0; padding: 0" v-if="plannungs.length" /> -->
                    <!-- <div style="margin-bottom:40px;" class="row flex justify-between px-view mx-auto bottom-drags">

                    </div> -->
                    <div style="width: 100%" class="bottom-drags">
                            <draggable
                                style="
                                    width: 100%;
                                    flex-wrap: wrap;
                                    min-height: 300px;
                                "
                                class="flex justify-between"
                                v-model="plannungs"
                                group="parents"
                                @start="drag = true"
                                @end="drag = false"
                                 @change="checkMove"
                                :move="allowMovedElement"
                            >
                                <PlanningCardItemVue
                                    v-for="item in plannungs"
                                    :key="item.id"
                                    :plannung="item"
                                ></PlanningCardItemVue>
                            </draggable>
                        </div>
                </div>
            </div>
        </div>

        <Notification />
        <RegistrationView />
        <StatusModal
            :statuses="statuses"
            :status_id="status_id"
            @changeStatus="updateStatus"
        />
    </div>
</template>

<script>
import PlannungCardItem from "./PlannungCardItem";
import draggable from "vuedraggable";

import axios from "../../axios";
import throttle from "lodash/throttle";
import pickBy from "lodash/pickBy";

import PlanningCardItemVue from "./Components/PlanningCardItem.vue";
import Notification from "./Components/Notification.vue";
import RegistrationView from "./Components/RegistrationView.vue";
import Filters from "./Components/Filter/Filters.vue";
// import DaateRangeFilter from './Components/Filter/DateRangeFilter.vue'
import MultiSelectFilter from "./Components/Filter/MultiSelectFilter.vue";
import SingleSelectFilter from "./Components/Filter/SingleSelectFilter.vue";
import Export from "./Components/Export.vue";
import Search from "./Components/Search.vue";
import StatusModal from "./Components/StatusModal.vue";
import Status from "./Components/Status.vue";

export default {
    name: "Plannung",
    components: {
        PlannungCardItem,
        draggable,
        PlanningCardItemVue,
        Notification,
        RegistrationView,
        Filters,
        MultiSelectFilter,
        SingleSelectFilter,
        // DaateRangeFilter,
        Export,
        Search,
        Status,
        StatusModal,
    },
    computed: {
        user(){
            return this.$store.getters['user'];
        },
    },
    data() {
        return {
            background: "#0063A7",
            status_id: 1,
            selected_playgroup: 0,
            role: localStorage.getItem("role"),
            statuses: [
                {
                    status_id: 1,
                    status_name: "Neu",
                },
                {
                    status_id: 2,
                    status_name: "Aktiv-Provisorisch",
                },
                {
                    status_id: 3,
                    status_name: "Aktiv-Bereit zur Besprechung",
                },
                {
                    status_id: 4,
                    status_name: "Abgeschlossen",
                },
            ],
            plannungsHeader: [],
            plannungs: [],
            planungsFilter: [],
            activeDivision: null
        };
    },
    watch: {
        status_id() {
            // if (this.status_id === 1) {
            //     this.background = '#0063A7'
            // } else if (this.status_id === 2) {
            //     this.background = '#E62D4F'
            // } else if (this.status_id === 3) {
            //     this.background = '#F2A402'
            // } else {
            //     this.background = '#AEC90B'
            // }
            // axios.post('/api/plannung/status/'+this.status_id).then(res => {
            // }).catch(err => {})
        },
        plannungsHeader: {
            deep: true,
            handler: throttle(function (newVal, oldVal) {
                const updatedOrders = newVal.map((pl, index) => {
                    return { id: pl.id, order: index };
                });
                axios
                    .post("api/plannung/order", pickBy(updatedOrders))
                    .then((res) => {})
                    .catch((err) => {});
            }, 1000),
        },
        selected_playgroup: {
            handler: throttle(function (newVal, oldVal) {
                if (newVal === 0) {
                    this.plannungsHeader = this.planungsFilter.filter(
                        (item, index) => index <= 1
                    );
                    this.plannungs = this.planungsFilter.filter(
                        (item, index) => index > 1
                    );
                } else {
                    this.plannungsHeader = this.planungsFilter.filter(
                        (val) => val.id === newVal
                    );
                    this.plannungs = [];
                }
            }, 1000),
        },
        plannungs: {
            deep: true,
            handler: throttle(function (newVal, oldVal) {
                const updatedOrders = newVal.map((pl, index) => {
                    return { id: pl.id, order: index + 2 };
                });
                axios
                    .post("api/plannung/order", pickBy(updatedOrders))
                    .then((res) => {})
                    .catch((err) => {});
            }, 1000),
        },
    },
    mounted() {
        // this.getStatus()
        this.getPlannung();
    },
    methods: {
        changeDivision() {
            this.getPlannung();
            this.getStatus();
        },
        updateStatus(statusId) {
           const division_id = this.$route.query.classification ? this.$route.query.classification : this.activeDivision.id;

            axios
                .put(`/api/division-status/${division_id}/update`, {
                    status_id: statusId,
                })
                .then((res) => {
                    // this.getPlannung();
                    this.status_id = statusId;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getStatus() {
            // axios.get('/api/plannung/get-status').then(res => {
            //     this.status_id  = res.data.status != undefined ?  res.data.status : 1;

            // }).catch(err => {
            //     console.log(err)
                // })

            const division_id = this.$route.query.classification ? this.$route.query.classification : this.activeDivision.id;

            axios
                .get(`/api/getDivision/${division_id}`)
                .then((res) => {
                    this.status_id = res.data.data.status_id;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getPlannung() {
            const query = { ...this.$route.query };
            const queryString = new URLSearchParams(query).toString();

            axios
                .get(`/api/plannung?${queryString}`)
                .then((res) => {
                    const data = res.data.playgroups
                    this.planungsFilter = data;

                    this.status_id = res.data.active_division ? res.data.active_division.status_id : 1;
                    this.activeDivision = res.data.active_division;

                    console.log(this.activeDivision)

                    // this.plannungsHeader = data.filter(
                    //     (item, index) => index <= 1
                    // );
                    // this.plannungs = data.filter(
                    //     (item, index) => index > 1
                    // );

                    this.plannungs = res.data.playgroups
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        allowMovedElement() {
            return this.role === "admin";
        },
        checkMove(evt) {
            const elId = evt.removed?.element?.id;
            if (!elId) {
                return;
            }
            this.plannungsHeader.forEach((p, index) => {
                if (p.id === elId) {
                    if (index === 0 || index === 2) {
                        const tmpEl = this.plannungsHeader[1];
                        this.plannungsHeader = [
                            this.plannungsHeader[0],
                            this.plannungsHeader[2],
                        ];
                        this.plannungs = [
                            tmpEl,
                            ...this.plannungs.filter((pl) => pl.id !== elId),
                        ];
                    } else if (index === 1) {
                        const tmpEl = this.plannungsHeader[2];
                        this.plannungsHeader = [
                            this.plannungsHeader[0],
                            this.plannungsHeader[1],
                        ];
                        this.plannungs = [
                            tmpEl,
                            ...this.plannungs.filter((pl) => pl.id !== elId),
                        ];
                    }
                }
            });
        },
    },
};
</script>

<style scoped>
.plannung {
    margin-top: 30px;
    margin-bottom: 30px;
}
.plannung-width-348 {
    width: 429px;
    margin-right: 43px;
    margin-bottom: 43px;
}
.first-selection {
    border-radius: 5px;
    border: 1px solid #c2c5c6;
    padding-inline: 10px;
}

.plannung-card {
    width: 400px;
    padding-block: 5px;
    align-items: center;
}

.status-select {
    width: 190px !important;
    color: white;
    top: 0px;
    outline: unset !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    cursor: pointer;
    position: relative;
}

.custom-select:after {
    font-size: 25px;
    color: white;
    transform: rotate(90deg);
    right: 7px;
    top: 1px;
    padding: 0px 0px 33px 9px;
    border-bottom: 1px solid #ddd;
    position: absolute;
    content: ">";
    width: 30px;
    height: 0px;
    font-weight: 300;
    pointer-events: none;
    cursor: pointer;
}

.custom-select {
    position: relative;
    padding-right: 5px;
}

.horizontal-line {
    border-top: 1px solid #c2c5c6;
    margin-top: 20px;
    margin-bottom: 20px;
    margin-inline: 0;
    padding-inline: 0;
    width: 100%;
}

.plannung-cols-6 {
    width: 50%;
    gap: 12px;
}

.plannung-cols-4 {
    width: 42%;
}
</style>

<style lang="scss" scoped>
.plannung {
    margin-top: 0px;
}
.planning {
    padding: 25px 32px;

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

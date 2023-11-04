<template>
    <div>
        <datatable
            :columns="columns"
            :data="row"
            :filter="filter"
            :per-page="perPage"
            style="text-align: left !imporant"
        ></datatable>
        <datatable-pager v-model="page" type="abbreviated"></datatable-pager>
    </div>
</template>
<script>
import axios from "../../axios";
export default {
    data() {
        return {
            columns: [
                {
                    label: "Erstellungsdatum",
                    field: "-",
                    headerClass: "table-header",
                },
                {
                    label: "ID",
                    field: "uniq_id",
                    headerClass: "table-header",
                },
                {
                    label: "Name",
                    field: "name",
                    headerClass: "table-header",
                },
                {
                    label: "Kontaktperson",
                    field: "first_name+last_name",
                    headerClass: "table-header",
                },
                {
                    label: "Telefonnummer",
                    field: "telephone",
                    headerClass: "table-header",
                },
                {
                    label: "E-Mail Adresse",
                    field: "email",
                    headerClass: "table-header",
                },
                {
                    label: "Status",
                    field: "email",
                    headerClass: "table-header",
                },
            ],
            rows: [],
            page: 1,
            filter: "",
            perPage: 12,
        };
    },
    methods: {
        showCompanies: function () {
            axios.get("/api/company").then(
                function (res) {
                    this.rows = res.data;
                    console.log(res.data);
                }.bind(this)
            );
        },
    },
    created: function () {
        this.showCompanies();
    },
};
</script>

<style scoped>
.table-header {
    text-align: left !important;
}
</style>

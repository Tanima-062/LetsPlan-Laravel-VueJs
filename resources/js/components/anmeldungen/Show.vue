<template>
    <div class="view">
        <div class="head">
            <Back style="margin-left: 30px; padding-top: 34px" />
            <h4 class="title">Anmeldung Details</h4>
        </div>

        <div class="content" v-if="register">
            <div class="details-row" style="padding-top: 40px">
                <div class="details">
                    <p class="label">Erstellungsdatum</p>
                    <p class="value">{{ register.created_at | formatDate }}</p>
                </div>
                <div class="details">
                    <p class="label">Anmeldungs-ID</p>
                    <p class="value">{{ register.prefix_id }}</p>
                </div>
                <div class="details">
                    <p class="label">Einteilung</p>
                    <p class="value" v-if="register.division">
                        {{ register.division.name }}
                    </p>
                </div>
            </div>

            <h5 class="field-title">Kind</h5>

            <div class="details-row" style="padding-top: 32px">
                <div class="details">
                    <p class="label">Vorname</p>
                    <p class="value">{{ register.child_first_name }}</p>
                </div>
                <div class="details">
                    <p class="label">Nachname</p>
                    <p class="value">{{ register.child_last_name }}</p>
                </div>
                <div class="details">
                    <p class="label">Geschlecht</p>
                    <p class="value" v-if="register.gender_id == 1">Junge</p>
                    <p class="value" v-if="register.gender_id == 2">Mädchen</p>
                </div>
            </div>

            <div class="details-row" style="padding-top: 40px">
                <div class="details">
                    <p class="label">Geburtsdatum</p>
                    <p class="value">
                        {{ register.child_birthday | formatDate }}
                    </p>
                </div>
                <div class="details">
                    <p class="label">Nationalität</p>
                    <p class="value">{{ register.nationality.name }}</p>
                </div>
                <div class="details">
                    <p class="label">Muttersprache</p>
                    <p class="value">
                        <template
                            v-if="
                                register.mother_tongue &&
                                register.mother_tongue.name
                            "
                        >
                            {{ register.mother_tongue.name }}
                        </template>
                    </p>
                </div>
            </div>

            <h5 class="field-title">Eltern</h5>

            <div class="details-row" style="padding-top: 32px">
                <div class="details">
                    <p class="label">Vorname Mutter</p>
                    <p class="value">{{ register.mother_first_name }}</p>
                </div>
                <div class="details">
                    <p class="label">Nachname Mutte</p>
                    <p class="value">{{ register.mother_last_name }}</p>
                </div>
            </div>

            <div class="details-row" style="padding-top: 40px">
                <div class="details">
                    <p class="label">Vorname Vater</p>
                    <p class="value">{{ register.father_first_name }}</p>
                </div>
                <div class="details">
                    <p class="label">Nachname Vater</p>
                    <p class="value">{{ register.father_last_name }}</p>
                </div>
            </div>

            <h5 class="field-title">Adresse</h5>

            <div class="details-row" style="padding-top: 32px">
                <div class="details">
                    <p class="label">Optionaler Adresszusatz</p>
                    <p class="value">{{ register.optional_address }}</p>
                </div>
                <div class="details">
                    <p class="label">Strasse</p>
                    <p class="value">{{ register.street }}</p>
                </div>
                <div class="details">
                    <p class="label">Hausnummer</p>
                    <p class="value">{{ register.street_number }}</p>
                </div>
            </div>

            <div class="details-row" style="padding-top: 40px">
                <div class="details">
                    <p class="label">Postleitzahl</p>
                    <p class="value">{{ register.postal_code }}</p>
                </div>
                <div class="details">
                    <p class="label">Ort</p>
                    <p class="value">{{ register.place }}</p>
                </div>
            </div>

            <h5 class="field-title">Kontaktdaten</h5>

            <div class="details-row" style="padding-top: 32px">
                <div class="details">
                    <p class="label">E-Mail Adresse</p>
                    <p class="value">{{ register.email }}</p>
                </div>
                <div class="details">
                    <p class="label">Telefonnummer</p>
                    <p class="value">
                        {{ register.phone_number_prefix }}
                        {{ register.phone_number }}
                    </p>
                </div>
                <div class="details">
                    <p class="label">Mobiltelefonnummer</p>
                    <p
                        class="value"
                        v-if="
                            register.mobile_phone_number_prefix &&
                            register.mobile_phone_number
                        "
                    >
                        {{ register.mobile_phone_number_prefix }}
                        {{ register.mobile_phone_number }}
                    </p>
                </div>
            </div>

            <h5 class="field-title">Weitere Anmeldeinformationen</h5>

            <div class="details-row" style="padding-top: 32px">
                <div class="details">
                    <p class="label">Präferierte Spielgruppe</p>
                    <p class="value" v-if="register.selected_spielgruppen">
                        {{ register.selected_spielgruppen.name }}
                    </p>
                    <p class="value" v-else>Keine</p>
                </div>
                <div class="details">
                    <p class="label">Anzahl Spielgruppenbesuche</p>
                    <p class="value" v-if="register.semester_id == 1">
                        1 x wöchentlich 2 ½ h à CHF 360.- pro Semester
                    </p>
                    <p class="value" v-if="register.semester_id == 2">
                        2 x wöchentlich 2 ½ h à CHF 720.- pro Semester
                    </p>
                    <p class="value" v-if="register.semester_id == 3">
                        3 x wöchentlich 2 ½ h à CHF 1’080.- pro Semester
                    </p>
                </div>
                <div class="details">
                    <p class="label">Mitgliedschaft</p>
                    <p class="value" v-if="register.membership_id == 1">Neu</p>
                    <p class="value" v-if="register.membership_id == 2">
                        Mitglied
                    </p>
                </div>
            </div>

            <!-- Hours Table -->
            <div class="openings-table" style="margin-top: 32px">
                <div class="head">
                    <div class="table-row">
                        <h5 class="column" style="width: 25%"></h5>
                        <h5 class="column" style="width: 15%">Montag</h5>
                        <h5 class="column" style="width: 15%">Dienstag</h5>
                        <h5 class="column" style="width: 15%">Mittwoch</h5>
                        <h5 class="column" style="width: 15%">Donnerstag</h5>
                        <h5 class="column" style="width: 15%">Freitag</h5>
                    </div>
                </div>
                <div class="content">
                    <div class="table-row">
                        <div class="column" style="width: 25%">
                            <template
                                v-if="
                                    register.selected_spielgruppen &&
                                    register.selected_spielgruppen
                                        .morning_time_range
                                "
                            >
                                Vormittag ({{
                                    register.selected_spielgruppen
                                        .morning_time_range | formatTimeRange
                                }})
                            </template>
                            <template v-else> Vormittag </template>
                        </div>
                        <div
                            class="column"
                            style="width: 15%"
                            v-for="day in 5"
                            :key="day"
                        >
                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 22 22"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                v-if="
                                    register.slots.find(
                                        (date) =>
                                            date.day_id == day &&
                                            date.hour_id == 1
                                    )
                                "
                            >
                                <path
                                    d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z"
                                    fill="#E97D32"
                                />
                            </svg>
                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 22 22"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                v-else
                            >
                                <path
                                    d="M7 9.75H5.75V12.25H7V9.75ZM15 12.25H16.25V9.75H15V12.25ZM7 12.25H15V9.75H7V12.25ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z"
                                    fill="#4C85A7"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="table-row">
                        <div class="column" style="width: 25%">
                            <template
                                v-if="
                                    register.selected_spielgruppen &&
                                    register.selected_spielgruppen
                                        .afternoon_time_range
                                "
                            >
                                Nachmittag ({{
                                    register.selected_spielgruppen
                                        .afternoon_time_range | formatTimeRange
                                }})
                            </template>
                            <template v-else> Nachmittag </template>
                        </div>
                        <div
                            class="column"
                            style="width: 15%"
                            v-for="day in 5"
                            :key="day"
                        >
                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 22 22"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                v-if="
                                    register.slots.find(
                                        (date) =>
                                            date.day_id == day &&
                                            date.hour_id == 2
                                    )
                                "
                            >
                                <path
                                    d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z"
                                    fill="#E97D32"
                                />
                            </svg>
                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 22 22"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                v-else
                            >
                                <path
                                    d="M7 9.75H5.75V12.25H7V9.75ZM15 12.25H16.25V9.75H15V12.25ZM7 12.25H15V9.75H7V12.25ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z"
                                    fill="#4C85A7"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="details-row" style="padding-top: 40px">
                <div class="details">
                    <p class="label">Bemerkungen Eltern</p>
                    <p class="value">{{ register.note }}</p>
                </div>
            </div>

            <h5 class="field-title">Einteilungsinformationen</h5>

            <div
                class="details-row"
                style="padding-top: 32px; margin-bottom: 70px"
            >
                <div class="details">
                    <p class="label">Naheliegendste Spielgruppe</p>
                    <a
                        v-if="register.closest_spielgruppen"
                        class="value"
                        target="_blank"
                        :href="`/spielgruppen/show/${register.closest_spielgruppen.id}`"
                        >{{ register.closest_spielgruppen.name }}</a
                    >
                </div>
                <div class="details">
                    <p class="label">Eingeteilte Spielgruppe</p>
                    <a
                        v-if="register.spielgruppen[0]"
                        class="value"
                        target="_blank"
                        :href="`/spielgruppen/show/${register.spielgruppen[0].id}`"
                        >{{ register.spielgruppen[0].name }}</a
                    >
                </div>
                <div class="details">
                    <p class="label">Interne Bemerkungen</p>
                    <p class="value">{{ register.internal_comments }}</p>
                </div>
            </div>

            <div class="details-row" v-if="user.role_id == 1">
                <div class="details">
                    <router-link
                        class="edit-button"
                        :to="`/anmeldungen/edit/${register.id}`"
                    >
                        <svg
                            width="26"
                            height="26"
                            viewBox="0 0 26 26"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M1.33203 24.6667H0.0820312V25.9167H1.33203V24.6667ZM1.33203 19.3333L0.448148 18.4494L0.0820312 18.8156V19.3333H1.33203ZM18.6654 2L19.5492 1.11612L18.6654 0.232233L17.7815 1.11612L18.6654 2ZM23.9987 7.33333L24.8826 8.21722L25.7665 7.33333L24.8826 6.44945L23.9987 7.33333ZM6.66536 24.6667V25.9167H7.18313L7.54925 25.5505L6.66536 24.6667ZM2.58203 24.6667V19.3333H0.0820312V24.6667H2.58203ZM17.7815 2.88388L23.1148 8.21722L24.8826 6.44945L19.5492 1.11612L17.7815 2.88388ZM6.66536 23.4167H1.33203V25.9167H6.66536V23.4167ZM23.1148 6.44945L19.1148 10.4494L20.8826 12.2172L24.8826 8.21722L23.1148 6.44945ZM19.1148 10.4494L5.78148 23.7828L7.54925 25.5505L20.8826 12.2172L19.1148 10.4494ZM2.21591 20.2172L15.5492 6.88388L13.7815 5.11612L0.448148 18.4494L2.21591 20.2172ZM15.5492 6.88388L19.5492 2.88388L17.7815 1.11612L13.7815 5.11612L15.5492 6.88388ZM20.8826 10.4494L15.5492 5.11612L13.7815 6.88388L19.1148 12.2172L20.8826 10.4494Z"
                                fill="white"
                            />
                        </svg>
                        <span>Bearbeiten</span>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import Back from "./components/Back.vue";
import dayjs from "dayjs";

export default {
    data() {
        return {
            register: null,
        };
    },

    filters: {
        formatDate: (value) => {
            return dayjs(value).format("DD.MM.YYYY");
        },
        leadzero: (value) => {
            return String(value).padStart(5, "0");
        },
        formatTimeRange(value) {
            if (value) {
                return value.replace(/-/g, " - ") + " Uhr";
            }
            return value;
        },
    },
    computed: {
        user() {
            return this.$store.getters["user"];
        },
    },
    mounted() {
        axios
            .get(`/api/anmeldungens/get/${this.$route.params.id}`)
            .then((res) => {
                this.register = res.data;
                console.log(res.data);
            })
            .catch((err) => {
                console.log(err);
            });
    },
    components: { Back },
};
</script>

<style lang="scss" scoped>
.view {
    background-color: white !important;
    min-height: 100vh;

    > .head {
        background-color: #e9f7fe;
        margin-top: 40px;

        .title {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 22px;
            line-height: 25px;
            color: #063f5f;
            margin: 42px 0px 0px 24px;
        }
    }

    > .content {
        background-color: #e9f7fe;
        padding-left: 24px;
        padding-right: 24px;
        padding-bottom: 23px;

        .details-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            width: 100%;

            .details {
                .label {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    line-height: 27px;
                    color: #16a9b7;
                    margin-bottom: 5px;
                }

                .value {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 15px;
                    line-height: 23px;
                    color: #2c5b7d;
                    margin: 0px;
                }

                .edit-button {
                    display: flex;
                    position: relative;
                    width: 100%;
                    height: 40px;
                    background: #e97d32;
                    text-decoration: none;
                    align-items: center;
                    justify-content: center;

                    svg {
                        position: absolute;
                        top: 50%;
                        left: 2%;
                        transform: translateY(-50%);
                        margin-left: 0px;
                    }

                    span {
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 18px;
                        line-height: 27px;
                        color: #ffffff;
                        justify-self: center;
                    }
                }
            }
        }

        .field-title {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 21px;
            color: #063f5f;
            margin-top: 48px;
        }

        .openings-table {
            display: flex;
            flex-direction: column;
            gap: 8px;

            .table-row {
                display: flex;
                align-items: center;
                background-color: white;
                padding: 11px 16px;
            }

            > .head {
                width: 100%;

                .table-row {
                    .column {
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 18px;
                        line-height: 27px;
                        display: flex;
                        align-items: center;
                        color: #16a9b7;
                        margin: 0px;
                    }
                }
            }

            > .content {
                width: 100%;

                .table-row {
                    border-top: 1px solid #d3e3ec;

                    .column {
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2c5b7d;
                    }
                }

                > .table-row:first-child {
                    border-top: none;
                }
            }
        }
    }
}
</style>

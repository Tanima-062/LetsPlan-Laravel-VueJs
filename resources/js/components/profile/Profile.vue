<template>
    <div class="supervisor-card-wrapper profile-card py-30 pl-30">
        <span
            class="flex items-center"
            style="cursor: pointer; margin-bottom: 30px"
            @click="goBack"
            ><svg
                width="26.67px"
                height="20px"
                viewBox="0 0 31 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="mr-3"
            >
                <path
                    d="M2.66602 12L29.3327 12M2.66602 12L12.666 2M2.66602 12L12.666 22"
                    stroke="#2C5B7D"
                    stroke-width="2.5"
                    stroke-linecap="square"
                />
            </svg>
            <span class="prev-page">Zurück</span></span
        >
        <h1 class="mb-4">Profil-Einstellungen</h1>

        <div class="row flex mt-3">
            <div class="col-4">
                <div class="flex items-start" v-if="user_profile">
                    <file-upload-field
                        :error="
                            errors && errors['image'] ? errors['image'][0] : ''
                        "
                        :filepath="
                            user_profile.image !== null
                                ? user_profile.image
                                : ''
                        "
                        @handleImageUpload="previewImage"
                        :key="fileUploadKey"
                    />
                    <!-- <div class="flex flex-col">
                        <p class="prompt" style="margin-bottom: 12px">Bild</p>
                        <div
                            class="img-option"
                            v-show="user_profile.image !== null"
                        >
                            <label
                                class="upload"
                                style="cursor: pointer"
                                for="inpFile"
                            >
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 23"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M17.8333 8.66675H16.5833V9.91675H17.8333V8.66675ZM19.5408 16.3347L18.4588 16.9606L19.7106 19.1247L20.7926 18.4988L19.5408 16.3347ZM7.22622 10.0839L8.41475 10.471L9.18911 8.094L8.00058 7.70682L7.22622 10.0839ZM3.20743 18.4988L4.28943 19.1247L5.54125 16.9606L4.45924 16.3347L3.20743 18.4988ZM12 11.0001L12.8839 10.1162L12 9.23232L11.1161 10.1162L12 11.0001ZM10.75 21.5001V22.7501H13.25V21.5001H10.75ZM14.6161 15.384L15.5 16.2678L17.2678 14.5001L16.3839 13.6162L14.6161 15.384ZM7.61612 13.6162L6.73223 14.5001L8.5 16.2678L9.38388 15.384L7.61612 13.6162ZM7.41667 7.50008C7.41667 4.96878 9.46869 2.91675 12 2.91675V0.416748C8.08798 0.416748 4.91667 3.58806 4.91667 7.50008H7.41667ZM12 2.91675C14.5313 2.91675 16.5833 4.96878 16.5833 7.50008H19.0833C19.0833 3.58806 15.912 0.416748 12 0.416748V2.91675ZM16.5833 7.50008V8.66675H19.0833V7.50008H16.5833ZM4.91667 7.50008V8.66675H7.41667V7.50008H4.91667ZM17.8333 9.91675C19.7203 9.91675 21.25 11.4464 21.25 13.3334H23.75C23.75 10.0657 21.101 7.41675 17.8333 7.41675V9.91675ZM21.25 13.3334C21.25 14.6087 20.5545 15.7483 19.5408 16.3347L20.7926 18.4988C22.5686 17.4714 23.75 15.5128 23.75 13.3334H21.25ZM2.75 13.3334C2.75 11.4464 4.27969 9.91675 6.16667 9.91675V7.41675C2.89898 7.41675 0.25 10.0657 0.25 13.3334H2.75ZM6.16667 9.91675C6.53857 9.91675 6.89423 9.97571 7.22622 10.0839L8.00058 7.70682C7.42161 7.51821 6.80476 7.41675 6.16667 7.41675V9.91675ZM4.45924 16.3347C3.44549 15.7483 2.75 14.6087 2.75 13.3334H0.25C0.25 15.5128 1.43143 17.4714 3.20743 18.4988L4.45924 16.3347ZM10.75 11.0001V21.5001H13.25V11.0001H10.75ZM16.3839 13.6162L12.8839 10.1162L11.1161 11.884L14.6161 15.384L16.3839 13.6162ZM11.1161 10.1162L7.61612 13.6162L9.38388 15.384L12.8839 11.884L11.1161 10.1162Z"
                                        fill="#063F5F"
                                    />
                                </svg>

                                <p>Bild ändern</p>
                            </label>
                            <div
                                class="upload"
                                @click.prevent="clearImage"
                                style="cursor: pointer"
                            >
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 22 23"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M9.91406 9.66667V8.41667H7.41406V9.66667H9.91406ZM7.41406 16.6667V17.9167H9.91406V16.6667H7.41406ZM14.5807 9.66667V8.41667H12.0807V9.66667H14.5807ZM12.0807 16.6667V17.9167H14.5807V16.6667H12.0807ZM19.2474 5V3.75H16.7474V5H19.2474ZM5.2474 5V3.75H2.7474V5H5.2474ZM1.66406 3.75H0.414062V6.25H1.66406V3.75ZM20.3307 6.25H21.5807V3.75H20.3307V6.25ZM13.2474 5V6.25H15.7474V5H13.2474ZM6.2474 5V6.25H8.7474V5H6.2474ZM7.41406 9.66667V16.6667H9.91406V9.66667H7.41406ZM12.0807 9.66667V16.6667H14.5807V9.66667H12.0807ZM16.7474 5V19H19.2474V5H16.7474ZM15.6641 20.0833H6.33073V22.5833H15.6641V20.0833ZM5.2474 19V5H2.7474V19H5.2474ZM6.33073 20.0833C5.73242 20.0833 5.2474 19.5983 5.2474 19H2.7474C2.7474 20.979 4.35171 22.5833 6.33073 22.5833V20.0833ZM16.7474 19C16.7474 19.5983 16.2624 20.0833 15.6641 20.0833V22.5833C17.6431 22.5833 19.2474 20.979 19.2474 19H16.7474ZM1.66406 6.25H20.3307V3.75H1.66406V6.25ZM15.7474 5V3.83333H13.2474V5H15.7474ZM12.1641 0.25H9.83073V2.75H12.1641V0.25ZM6.2474 3.83333V5H8.7474V3.83333H6.2474ZM9.83073 0.25C7.85171 0.25 6.2474 1.85431 6.2474 3.83333H8.7474C8.7474 3.23502 9.23242 2.75 9.83073 2.75V0.25ZM15.7474 3.83333C15.7474 1.85431 14.1431 0.25 12.1641 0.25V2.75C12.7624 2.75 13.2474 3.23502 13.2474 3.83333H15.7474Z"
                                        fill="#063F5F"
                                    />
                                </svg>

                                <p>Bild entfernen</p>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="row flex items-start col-8 personal-info">
                <div class="col-6">
                    <div class="form-group">
                        <p class="prompt">Vorname</p>
                        <input
                            v-if="user_profile"
                            v-model="user_profile.first_name"
                            class="form-control form-input-bordered"
                            readonly
                            style="pointer-events: none"
                        />
                    </div>
                </div>
                <div class="form-group col-6">
                    <p class="prompt">Nachname</p>
                    <input
                        v-if="user_profile"
                        v-model="user_profile.last_name"
                        class="form-control form-input-bordered"
                        readonly
                        style="pointer-events: none"
                    />
                </div>
            </div>
            <div style="padding-top: 20px" class="form-group">
                <p class="prompt">Benutzertyp</p>
                <p class="type" v-if="user_profile">
                    {{ user_profile.role_name_de }}
                </p>
            </div>
        </div>

        <div class="row flex">
            <div class="flex-grow-0">
                <p class="section-form-title">E-Mail Adresse ändern</p>
            </div>
            <div class="flex">
                <div class="col-4 form-group">
                    <p class="prompt">E-Mail</p>
                    <input
                        v-if="user_profile"
                        v-model="user_profile.email"
                        class="form-control form-input-bordered"
                    />
                </div>
                <div class="col-4 form-group">
                    <p class="prompt">Neue E-Mail Adresse</p>
                    <input
                        v-model="new_email"
                        class="form-control form-input-bordered"
                        style="
                            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0),
                                inset 0 0 0 100px rgba(255, 255, 255, 1);
                        "
                    />
                    <label
                        class="text-danger"
                        v-if="errors && errors['email']"
                        >{{ errors["email"][0] }}</label
                    >
                </div>
            </div>
        </div>

        <div class="row flex">
            <div class="flex-grow-0">
                <p class="section-form-title">Passwort ändern</p>
            </div>
            <div class="password-info">
                <div class="form-group flex-grow-1 col-4">
                    <p class="prompt">Passwort</p>
                    <div style="position: relative; display: flex">
                        <input
                            id="old_password"
                            type="password"
                            v-model="password"
                            class="form-control form-input-bordered"
                            style="
                                box-shadow: inset 0 0 0 1px
                                        rgba(255, 255, 255, 0),
                                    inset 0 0 0 100px rgba(255, 255, 255, 1);
                            "
                        />
                        <div
                            style="
                                position: absolute;
                                left: 400px;
                                cursor: pointer;
                                top: 50%;
                                transform: translateY(-50%);
                            "
                            @click="showHideField('old_password')"
                        >
                            <eye-open
                                style="display: none"
                                id="old_password_eye_open"
                            />
                            <eye-close id="old_password_eye_close" />
                        </div>
                    </div>
                    <label
                        class="text-danger"
                        v-if="errors && errors['old_password']"
                        >Bestehendes Passwort muss ausgefüllt werden</label
                    >
                </div>

                <div class="form-group flex flex-grow-1 flex-wrap">
                    <div class="form-group col-4">
                        <p class="prompt">Neues Passwort</p>
                        <div style="position: relative; display: flex">
                            <input
                                id="password"
                                type="password"
                                v-model="new_password"
                                class="form-control form-input-bordered"
                            />
                            <div
                                style="
                                    position: absolute;
                                    left: 400px;
                                    cursor: pointer;
                                    top: 50%;
                                    transform: translateY(-50%);
                                "
                                @click="showHideField('password')"
                            >
                                <eye-open
                                    style="display: none"
                                    id="password_eye_open"
                                />
                                <eye-close id="password_eye_close" />
                            </div>
                        </div>
                        <label
                            class="text-danger"
                            v-if="errors && errors['password']"
                            >{{ errors["password"][0] }}</label
                        >
                    </div>

                    <div class="form-group col-4">
                        <p class="prompt">Neues Passwort bestätigen</p>
                        <div style="position: relative; display: flex">
                            <input
                                id="confirm_password"
                                type="password"
                                v-model="confirm_password"
                                class="form-control form-input-bordered"
                            />
                            <div
                                style="
                                    position: absolute;
                                    left: 400px;
                                    cursor: pointer;
                                    top: 50%;
                                    transform: translateY(-50%);
                                "
                                @click="showHideField('confirm_password')"
                            >
                                <eye-open
                                    style="display: none"
                                    id="confirm_password_eye_open"
                                />
                                <eye-close id="confirm_password_eye_close" />
                            </div>
                        </div>
                        <label
                            class="text-danger"
                            v-if="errors && errors['password_confirmation']"
                            >{{ errors["password_confirmation"][0] }}</label
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row flex justify-start items-center">
            <div class="form-group col-4" style="padding-right: 0">
                <button
                    class="button-save"
                    style="width: 436px; height: 40px"
                    @click="updateUser"
                >
                    Aktualisieren
                </button>
            </div>
            <div class="form-group col-4" style="padding-left: 5px">
                <button
                    @click="$router.go(-1)"
                    class="button-clear"
                    style="width: 436px; height: 40px"
                >
                    Abbrechen
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import { mapState } from "vuex";
import FileUploadField from "../../shared/FileUploadEdit";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import EyeClose from "./EyeClose.vue";
import EyeOpen from "./EyeOpen.vue";
export default {
    name: "Profile",
    mixins: [flushMessagesMixin],
    components: {
        FileUploadField,
        EyeOpen,
        EyeClose,
    },
    data() {
        return {
            new_email: "",
            new_password: "",
            password: "",
            confirm_password: "",
            switcher: 0,
            profile_image: "",
            errors: [],
            fileUploadKey: 0,
        };
    },

    computed: {
        ...mapState({
            user_profile: (state) => state.user,
        }),
    },
    methods: {
        showHideField(id) {
            if (
                document.getElementById(id).getAttribute("type") == "password"
            ) {
                document.getElementById(id).setAttribute("type", "text");
                document.getElementById(id + "_eye_open").style.display =
                    "block";
                document.getElementById(id + "_eye_close").style.display =
                    "none";
            } else {
                document.getElementById(id).setAttribute("type", "password");
                document.getElementById(id + "_eye_open").style.display =
                    "none";
                document.getElementById(id + "_eye_close").style.display =
                    "block";
            }
        },
        updateUser() {
            const formData = new FormData();
            formData.append(
                "email",
                this.new_email ? this.new_email : this.user_profile.email
            );
            formData.append("old_password", this.password);
            formData.append("password", this.new_password);
            formData.append("password_confirmation", this.confirm_password);
            formData.append("first_name", this.user_profile.first_name);
            formData.append("last_name", this.user_profile.last_name);
            formData.append("image", this.user_profile.image);
            formData.append(
                "email_notifications",
                this.user_profile.email_notifications == true ? 1 : 0
            );

            axios
                .post("/api/user/update", formData)
                .then((res) => {
                    this.successMessage(res.data);
                    this.errors = [];
                    this.password =
                    this.new_password =
                    this.confirm_password =
                    this.new_email = "";
                    this.getUser();
                })
                .catch((err) => {
                    const backend_errors = err.response.data.errors;
                    this.errors = Object.assign({}, backend_errors);
                });
        },
        getUser() {
            axios
                .get("/api/user")
                .then((res) => {
                    this.$store.dispatch("setUser", res.data.data);
                })
                .catch((err) => {
                    this.errorMessage(err.response.message);
                });
        },
        previewImage(image) {
            this.user_profile.image = image;
        },

        logout() {
            axios
                .post("/api/logout")
                .then(() => {
                    localStorage.setItem("token", "");
                    location.reload();
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        goBack() {
            this.$router.push(`/startseite`);
        },
    },
};
</script>

<style lang="scss">
.profile-card {
    margin-top: 30px;
    margin-bottom: 50px;
    padding-right: 25px;
    padding-bottom: 15px;
    background-color: #e9f7fe;
    h1 {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 700;
        font-size: 22px;
        line-height: 25px;
        color: #063f5f;
    }
    .form-input-bordered {
        background: #fff;
        border-radius: 0px;
        border: none;
        height: 40px;
        font-family: "Nexa";
        color: #2c5b7d;
    }
    .type {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        color: #2c5b7d;
    }
    .upload-image {
        .image-preview {
            width: 104px !important;
            height: 104px !important;
            border-radius: 0px !important;
            &__image {
                width: 104px !important;
                height: 104px !important;
                object-fit: cover;
                border-radius: 0px !important;
            }
            .photo-selection {
                border-bottom-left-radius: 0px !important;
                border-bottom-right-radius: 0px !important;
            }
        }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group input {
        width: 436px;
        display: block;
    }

    .form-group input:focus {
        box-shadow: none;
    }

    .form-control {
        width: 100%;
    }
    .pt-5 {
        padding-top: 5px;
    }
    .section-form-title {
        font-family: "Ubuntu";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 21px;
        color: #063f5f;
        margin-bottom: 40px;
    }

    .switcher {
        gap: 43px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .btn {
        font-weight: 500;
        font-size: 15px;
        line-height: 25px;
        color: #ffffff;
        border-radius: 5px;
        padding: 7.5px 56.5px;
        &.btn-primary {
            margin-left: 20px;
            background: #aec90b;
        }
    }
    .text-danger {
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
        color: #e62d4f;
        text-decoration: none;
        border-bottom: 1.5px solid #e62d4f;
    }
    label.text-danger {
        border-bottom: 0;
    }

    .button-save {
        background: #e97d32 !important;
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        color: #ffffff;
        border-radius: 0;
        // margin-left: 12px;
    }
    .button-clear {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        background: #f8d3ba !important;
        // margin-left: 54px;
        color: #063f5f;
        border-radius: 0;
    }

    .prompt {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px !important;
        line-height: 27px !important;
        display: flex;
        align-items: center;
        color: #16a9b7 !important;
        margin-bottom: 5px;
    }
    .prev-page {
        width: 80px;
        height: 13px;
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 27px;
        display: flex;
        align-items: center;
        color: #2c5b7d;
        margin-left: 10px;
        margin-top: 4px;
    }
    .upload {
        display: flex;
        align-items: center;
        margin-bottom: 12px;

        p {
            margin-bottom: 0;
            margin-top: 2px;
            margin-left: 18.5px;
            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 23px;
            color: #2c5b7d;
        }
    }
}
</style>

<template>
    <!-- <span>
        <form class="bg-white shadow rounded-lg py-8 mx-auto login-form">
            <div  class="text-center">
                <img class="logo-login" src="/images/lets_play_logo.png"/>
                <p class="login-title text-center">Zum Fortfahren anmelden</p>
            </div>
            <div class="space-bottom-15">
                <label class="block mb-2 form-label" for="email">E-Mail</label>
                <input v-model="email" class="form-control form-input  w-full input-field" id="email" type="email"
                       name="email" required autofocus placeholder="E-Mail">
                <span class="text-danger"
                      v-if="errors && errors['email']">{{ errors['email'][0] }}</span>
            </div>

            <div class="space-bottom-15">
                <label class="block mb-2 form-label" for="password">Kennwort</label>
                <input v-model="password" class="form-control form-input w-full  input-field" id="password" type="password"
                       name="password" required placeholder="Kennwort">
                <span class="text-danger"
                      v-if="errors && errors['password']">{{ errors['password'][0] }}</span>
            </div>

            <button type="submit" @click.prevent="login" class="w-full btn btn-primary login-btn">
                Anmelden
            </button>
        </form>
        <div class="text-center mt-6">
            <router-link to="/forgot-password" class="text-primary dim font-bold no-underline forgot-password">
                Passwort vergessen?
            </router-link>
        </div>
    </span> -->
    <div class="login-parent">
        <div class="left-container">
            <img src="../../../../public/images/login.png" alt="login" />
        </div>
        <div class="right-container">
            <div class="header-img">
                <img src="../../../../public/images/new-logo.png" alt="logo" />
            </div>
            <div class="form-container">
                <h2>
                    Willkommen bei <br />
                    LetsPlan
                </h2>
                <div class="form">
                    <h4>Anmeldung</h4>
                    <form>
                        <div class="space-bottom-15">
                            <label class="block mb-2 form-label" for="email"
                                >E-Mail*</label
                            >
                            <input
                                v-model="email"
                                class="form-control form-input w-full input-field"
                                id="email"
                                type="email"
                                name="email"
                                required
                                autofocus
                                placeholder="E-Mail Adresse eingeben
"
                            />
                            <span
                                class="text-danger"
                                v-if="errors && errors['email']"
                                >{{ errors["email"][0] }}</span
                            >
                        </div>

                        <div class="space-bottom-15">
                            <label class="block mb-2 form-label" for="password"
                                >Passwort*
                            </label>
                            <input
                                v-model="password"
                                class="form-control form-input w-full input-field"
                                id="password"
                                type="password"
                                name="password"
                                required
                                placeholder="Passwort eingeben"
                            />
                            <span
                                class="text-danger"
                                v-if="errors && errors['password']"
                                >{{ errors["password"][0] }}</span
                            >
                        </div>

                        <button
                            type="submit"
                            @click.prevent="login"
                            class="login-btn"
                        >
                            Anmelden
                        </button>
                        <a @click="goToForgotPassword" class="pass-forgot"
                            >Passwort vergessen?</a
                        >
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "Login",
    mixins: [flushMessagesMixin],
    data() {
        return {
            email: "",
            password: "",
            errors: [],
        };
    },
    methods: {
        async login() {
            try {
                this.errors = [];
                const res = await axios.post("/api/login", {
                    email: this.email,
                    password: this.password,
                });
                if (res) {
                    await localStorage.setItem("token", res.data.token);
                    await localStorage.setItem("role", res.data.role_name);
                    location.reload();
                } else {
                    this.errorMessage(
                        "Something unexpected happened, please try again later"
                    );
                }
            } catch (err) {
                if (err.response.status === 422) {
                    this.errors = Object.assign({}, err.response.data.errors);
                    return;
                }
                this.loginErrorMessage(err.response.data.message);
                // this.errors = Object.assign({}, err.response.data.errors);
            }
        },
        goToForgotPassword() {
            this.$router.push("/forgot-password");
        },
    },
};
</script>

<style scoped lang="scss">
.login-parent {
    display: flex;
    align-items: center;
    justify-content: center;
}

.left-container,
.right-container {
    height: 100vh;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
}

.left-container {
    width: 60%;
    margin: auto;
    background: #2c5b7d;
    img {
        width: 60%;
        object-fit: contain;
    }
}

.right-container {
    margin-top: -200px;
    width: 40%;
    background: #fff;
    .form-container {
        text-align: left;

        width: 70%;
        h2 {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 35px;
            line-height: 40px;
            color: #e97d32;
            margin: 80px 0 56px;
        }
        .form {
            h4 {
                font-family: "Ubuntu";
                font-style: normal;
                font-weight: 700;
                font-size: 22px;
                line-height: 25px;
                color: #063f5f;
                margin-bottom: 2rem;
            }
            form label {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                display: flex;
                align-items: center;
                color: #16a9b7;
            }

            form input {
                border-radius: 0px;
                border: none;
                background-color: #e9f7fe;
                width: 436px;
                padding: 10px 20px;
                height: 40px;
                font-family: "Nexa";
            }

            form input:focus {
                background-color: #e9f7fe !important;
            }

            form input::placeholder {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;

                color: #87b1cb;
            }

            .login-btn {
                width: 436px;
                height: 40px;
                border-radius: 0px;
                margin-top: 25px;
                background: #e97d32;
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                color: #ffffff;
            }

            .pass-icon {
                object-fit: contain;
                margin-left: 5px;
                margin-top: -10px;
                width: 9px;
                height: 9px;
            }

            .pass-forgot {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 400;
                font-size: 12px;
                line-height: 18px;
                color: #a0a0a0;
                text-decoration: none;
                display: block;
                margin-top: 16px;
                cursor: pointer;
            }
        }
    }
}
</style>
